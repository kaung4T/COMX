<?php
/** Loads the WordPress Environment and Template */
define('WP_USE_THEMES', false);
require ('../../../../wp-blog-header.php');

use DynamicContentForElementor\DCE_Helper;

if (isset($_GET['template_id'])) {
    $template_id = intval($_GET['template_id']);
} else {
    $template_id = 0;
}
if (!empty($_GET['container'])) {
    $container = $_GET['container'];
} else {
    $container = 'body';
}

if (!empty($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);
} else {
    $user_id = get_current_user_id();
}

if (!empty($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
} else {
    $post_id = 0;
}

if (!empty($_GET['title'])) {
    $title = $_GET['title'].'.pdf';
} else {
    $title = time().'.pdf';
}
if (!empty($_GET['size'])) {
    $size = $_GET['size'];
} else {
    $size = 'a4';
}
if (!empty($_GET['orientation'])) {
    $orientation = $_GET['orientation'];
} else {
    $orientation = 'portrait';
}
if (!empty($_GET['margin'])) {
    $margin = $_GET['margin'];
} else {
    $margin = 0;
}

if (!empty($_GET['dest'])) {
    $dest = $_GET['dest'];
} else {
    $dest = 'I';
}

if (!empty($_GET['styles'])) {
    $styles = $_GET['styles'];
} else {
    $styles = 'elementor';
}

if (!empty($_GET['converter'])) {
    $converter = $_GET['converter'];
} else {
    $converter = 'dompdf';
}

//echo $post_id; die();

if ($template_id || $post_id) {

    if ($template_id) {
        if ($post_id) {
            $post = ' post_id="' . $post_id . '"';
        }
        if ($user_id) {
            $author = ' author_id="' . $user_id . '"';
        }
        $pdf_html = do_shortcode('[dce-elementor-template id="' . $template_id . '"' . $author . $post . ']');
    } else {
        // get HTML from current post
        //$pdf_html = do_shortcode('[dce-elementor-template id="' . $post_id . '"]');
        $cookies = array();
        foreach ( $_COOKIE as $name => $value ) {
            $cookies[] = new WP_Http_Cookie( array( 'name' => $name, 'value' => $value ) );
        }
        $response = wp_remote_get(get_permalink($post_id), array( 'cookies' => $cookies ));
        $page_body = wp_remote_retrieve_body($response); // may not work for internal calls
        
	if ($page_body) {
		// full page body
		$tmp = explode('<body',$page_body);
		$tmp = explode('>',end($tmp),2);
		$tmp = explode('</body>',end($tmp));
		$page_body = reset($tmp);
	} else {
		// fallback to elementor content
		$page_body = \Elementor\Plugin::$instance->frontend->get_builder_content( $post_id );
		$page_body = '<html><body>'.$page_body.'</body></html>';
	}

        $pdf_html = $page_body;
        //var_dump($page_body); die();
    }
    
    //var_dump($pdf_html); die();
    $pdf_html = DCE_Helper::get_dynamic_value($pdf_html);
    //var_dump($pdf_html); die();

    if ($styles != 'unstyled') {
        // add CSS
        $css_id = $template_id ? $template_id : $post_id;
        $css = DCE_Helper::get_post_css($css_id, ($styles == 'all'));
        // from flex to table
        $css .= '.elementor-section .elementor-container { display: table !important; width: 100% !important; }';
        $css .= '.elementor-row { display: table-row !important; }';
        $css .= '.elementor-column { display: table-cell !important; }';
        $css .= '.elementor-column-wrap, .elementor-widget-wrap { display: block !important; }';
        $css = str_replace(':not(.elementor-motion-effects-element-type-background) > .elementor-element-populated', ':not(.elementor-motion-effects-element-type-background)', $css);
        $css .= '.elementor-column .elementor-widget-image .elementor-image img { max-width: none !important; }';
        $pdf_html_precss = $pdf_html;
        if ($pdf_html_precss) {
            $cssToInlineStyles = new \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
            $pdf_html = $cssToInlineStyles->convert(
                $pdf_html,
                $css
            );
        } 
        //var_dump($pdf_html); die();
        if (!$pdf_html) {
            $pdf_html = $pdf_html_precss;
        }
    }
    
    if (!$template_id && $pdf_html) {
        //var_dump($container); die();


        $crawler = new \Symfony\Component\DomCrawler\Crawler($pdf_html);
        //$crawler = $crawler->filter($settings['tag_id']);
        // Remove download PDF BUTTON
        $crawler->filter('.elementor-widget-dce_pdf_button')->each(function (\Symfony\Component\DomCrawler\Crawler $crawler) {
            foreach ($crawler as $node) {
                $node->parentNode->removeChild($node);
            }
        });
        $pdf_html = $crawler->html();
        //var_dump($pdf_html); die();
        
        /*$crawler = new \Symfony\Component\DomCrawler\Crawler($pdf_html);
        // Fetch only wanted block
        $tmp = $crawler->filter($container)->each(function (\Symfony\Component\DomCrawler\Crawler $node, $i) {
            return $node->html();
        });
        //var_dump($tmp); die();
        $pdf_html = implode('', $tmp);*/
        
        
	$dom = new \PHPHtmlParser\Dom;
	$dom->load($pdf_html);
	$dom_elements = $dom->find($container);
        $tmp = '';
        if (!empty($dom_elements)) {
            foreach ($dom_elements as $a_elem) {
                if ($container == 'body') {
                    $tmp .= $a_elem->innerHtml;
                } else {
                    $tmp .= $a_elem->outerHtml;
                }
            }
        }
        $pdf_html = $tmp;
        
        
    }

    if (!$pdf_html) {
	    echo 'Content NOT found, please check selector or template';
	    die();
    }
    //var_dump($pdf_html); die();
    
    if ($margin) {
        $pdf_html .= '<style>@page { margin: '.$margin.'; }</style>';
    }
    if (is_rtl()) {
        // fix for arabic
        $pdf_html .= '<style>* { font-family: DejaVu Sans, sans-serif; }</style>';
    }
     
    
    //$pdf_html = 'hello';
    //var_dump($pdf_html); die();

    switch($converter) {
        case 'dompdf':
            // https://github.com/dompdf/dompdf
            //$auth = base64_encode("username:password");
            $context = stream_context_create(array(
              'ssl' => array(
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                // 'allow_self_signed'=> TRUE
              ),
              //'http' => array('header' => "Authorization: Basic $auth")
            ));
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', TRUE);
            $options->setIsRemoteEnabled(true);
            //$options->set('defaultFont', 'Courier');
            // instantiate and use the dompdf class
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->setHttpContext($context);
            $dompdf->loadHtml($pdf_html);
            $dompdf->set_option('isRemoteEnabled', TRUE);
            $dompdf->set_option('isHtml5ParserEnabled', true);
            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper($size, $orientation);
            // Render the HTML as PDF
            $dompdf->render();
            // Output the generated PDF to Browser 
            header("HTTP/1.1 200 OK");   
	        header("Content-type:application/pdf");
	        header("Content-Disposition:attachment;filename='".$title."'");
            $dompdf->stream($title);
            //$output = $dompdf->output();
            break;
    
        case 'tcpdf':
            
            // link image from url to path
            $site_url = site_url();
            $upload = wp_upload_dir();
            $pdf_html = str_replace('src="'.$site_url, 'src="'.$upload['basedir'], $pdf_html); 
            
            // from div to table
            $pdf_html = DCE_Helper::tablefy($pdf_html);
            $pdf_html .= '<style>table{ page-break-inside: auto; }</style>';

            // https://github.com/tecnickcom/tcpdf
            // create new PDF document
            $orientation = ($orientation == 'portrait') ? 'P' : 'L';
            $pdf = new \TCPDF($orientation, 'px', strtoupper($size), true, 'UTF-8', false);
            // set document information
            //$pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor(get_bloginfo('name'));
            $pdf->SetTitle($title);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            // add a page
            $pdf->AddPage();
            // output the HTML content
            //$pdf->SetFont('Helvetica', '', 10);
            $tagvs = array(
                'img' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)), 
                'picture' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)), 
                'section' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)), 
                'div' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)), 
                'p' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)), 
                'h1' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)),
                'h2' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)),
                'h3' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)),
                'h4' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)), 
                'h5' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)),
                'h6' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)),
                'ul' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)),
                'table' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)),
                'tr' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)),
                'td' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)),
                'th' => array(array('h' => 0, 'n' => 0), array('h' => 0, 'n' => 0)),
            );
            $pdf->setHtmlVSpace($tagvs);
            $pdf->writeHTML($pdf_html, true, false, true, false, '');
            // reset pointer to the last page
            $pdf->lastPage();
            // ---------------------------------------------------------
            //Close and output PDF document
            $pdf->Output($title, $dest);
            break;
        
        case 'browser':
            echo '<html><head><title>'.$title.'</title></head><body>'.$pdf_html.'
            <script>// Printer Settings Default off
                //user_pref("print.print_footerleft", "");
                //user_pref("print.print_footerright", "");
                //user_pref("print.print_headerleft", "");
                //user_pref("print.print_headerright", "");
                window.print();
            </script></body></html>';
            break;
    }
    die();
    
}

echo 'ERROR';
