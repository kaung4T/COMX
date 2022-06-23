<?php
namespace DynamicContentForElementor;
/**
 * Description of DCE_Trait_Plugin
 *
 */
trait DCE_Trait_FileSystem {
    
    

    public static function dir_to_array($dir, $hidden = false, $files = true) {
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = self::dir_to_array($dir . DIRECTORY_SEPARATOR . $value, $hidden, $files);
                } else {
                    if ($files) {
                        if (substr($value, 0, 1) != '.') { // hidden file
                            $result[] = $value;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public static function is_empty_dir($dirname) {
        if (!is_dir($dirname))
            return false;
        foreach (scandir($dirname) as $file) {
            if (!in_array($file, array('.', '..', '.svn', '.git')))
                return false;
        }
        return true;
    }

    /**
     * Function for including files
     *
     * @since 0.5.0
     */
    public static function file_include($file) {
        $path = DCE_PATH . $file;
        //echo $path;
        if (file_exists($path)) {
            include_once( $path );
        }
    }
    
    
    public static function zip_folder($options) {
        $defaults = array(
            'name'                 => '',
            'source_directory'     => '',
            'process_extensions'   => array('php', 'css', 'js', 'txt', 'md'),
            'zip_root_directory'   => '',
            'zip_temp_directory'   => plugin_dir_path( __FILE__ ),
            'download_filename'    => '',
            'exclude_directories'  => array('.git', '.svn', '.', '..'),
            'exclude_files'        => array('.git', '.svn', '.DS_Store', '.gitignore', '.', '..', '._.DS_Store'),
            'filename_filter'      => null,
            'file_contents_filter' => null,
            'post_process_action'  => null,
            'variables'            => array(),
            'zip_filename'    => '',
            'zip_foldername'    => '',
        );
        
        foreach ($defaults as $akey => $adef) {
            if (!isset($options[$akey])) {
                $options[$akey] = $adef;
            }
        }
        
        if ($options['zip_foldername']) {
            $options['zip_foldername'] .= '/';
        }

        $zip = new \ZipArchive;
        $res = $zip->open( $options['zip_filename'], \ZipArchive::CREATE && \ZipArchive::OVERWRITE );
        $iterator = new \RecursiveDirectoryIterator($options['source_directory']);
        foreach ( new \RecursiveIteratorIterator($iterator) as $filename ) {
                if ( in_array( basename( $filename ), $options['exclude_files'] ) ) {
                        continue;
                }
                foreach ( $options['exclude_directories'] as $directory ) {
                        if ( strstr( $filename, "/{$directory}/" ) ) {
                                continue 2;
                        }
                } // continue the parent foreach loop
                $zip_filename = str_replace( trailingslashit( $options['source_directory'] ), '', basename( $filename ) );
                //$zip_filename = apply_filters( 'zip_generator_process_filename-' . $this->slug, $zip_filename );
                //$contents = $this->process_file_contents( file_get_contents( $filename ), basename( $filename ) );
                //$contents = file_get_contents( $filename );
                $file_path	   = $filename->getRealPath();
                $relative_path = substr( $file_path, strlen( $options['source_directory'] ) );
                $zip->addFile( $file_path, $options['zip_foldername'].$relative_path );
                //$zip->addFromString( trailingslashit( $options['zip_root_directory'] ) . $zip_filename, $contents );
        }
        $zip->close();
    }
    
    static public function url_to_path($url) {
        return substr(get_home_path(),0,-1).wp_make_link_relative($url);
    }
    
}
