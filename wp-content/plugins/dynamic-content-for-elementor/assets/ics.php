<?php
/** Loads the WordPress Environment and Template */
define('WP_USE_THEMES', false);
require ('../../../../wp-blog-header.php');

// Escapes a string of characters
function escapeString($string) {
    return preg_replace('/([\,;])/','\\\$1', $string);
}
// Cut it
function shorter_version($string, $lenght) {
    if (strlen($string) >= $lenght) {
       return substr($string, 0, $lenght);
    } else {
       return $string;
    }
}

if (isset($_GET['element_id'])) {
    $element_id = $_GET['element_id'];
} else {
    $element_id = 0;
}

if (!empty($_GET['post_id'])) {
    $post_id = intval($_GET['post_id']);
} else {
    $post_id = 0;
}

if ($element_id && $post_id) {
    
    // static settings
    $settings = \DynamicContentForElementor\DCE_Helper::get_settings_by_id($element_id, $post_id);
    
    // dynamic settings
    // populate post for dynamic data
    global $post;
    $post = get_post($post_id);
    $created_date = get_post_time('Ymd\THis\Z', true, $post_id );
    // create an instance of widget to get his dynamic data
    include_once('../includes/widgets/DCE_Widget_Prototype.php');
    include_once('../includes/widgets/CONTENT/DCE_Widget_Calendar.php');
    $data = array('settings' => $settings, 'id' => $element_id);
    $widget = new \DynamicContentForElementor\Widgets\DCE_Widget_Calendar($data, array());
    $settings = $widget->get_settings_for_display();
    
    $title = escapeString(!empty($settings['dce_calendar_title']) ? $settings['dce_calendar_title'] : get_the_title($post_id));
    $description = escapeString(!empty($settings['dce_calendar_description']) ? strip_tags(nl2br($settings['dce_calendar_description'])) : '');
    $location = escapeString(!empty($settings['dce_calendar_location']) ? $settings['dce_calendar_location'] : '');
    $organiser = get_bloginfo('name');
    //
    $start = ($settings['dce_calendar_datetime_format'] != 'string') ? $settings['dce_calendar_datetime_start'] : $settings['dce_calendar_datetime_start_string'];
    $end = ($settings['dce_calendar_datetime_format'] != 'string') ? $settings['dce_calendar_datetime_end'] : $settings['dce_calendar_datetime_end_string'];
    
    $filename = urlencode( $title.'.ics' );
    
    ob_start();
    
    // Set the correct headers for this file    
    header('Content-Type: text/calendar; charset=utf-8');
    //header('Content-Type: application/octet-stream');
    header("Content-Transfer-Encoding: Binary");
    header('Content-Description: File Transfer');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public'); //header("Pragma: 0");    
    //header('Content-Disposition: inline; filename="'.$post->post_name.'.ics"');
    header('Content-Disposition: attachment; filename="'.$filename.'"');
    //ob_start();
?>BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Dynamic.ooo //NONSGML DCE Calendar //EN
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-TIMEZONE:<?php echo get_option('timezone_string'); echo PHP_EOL; ?>
X-WR-CALNAME:<?php echo get_bloginfo('name'); echo PHP_EOL; ?>
BEGIN:VEVENT
ORGANIZER:<?php echo escapeString($organiser); echo PHP_EOL; ?>
CREATED:<?php echo $created_date; echo PHP_EOL; ?>
URL;VALUE=URI:<?php echo get_permalink($post_id); echo PHP_EOL; ?>
DTSTART;VALUE=DATE:<?php echo date_i18n('Ymd\\THi00\\Z',strtotime($start), true); echo PHP_EOL; ?>
DTEND;VALUE=DATE:<?php echo date_i18n('Ymd\\THi00\\Z',strtotime($end), true); echo PHP_EOL; ?>
DTSTAMP:<?php echo date_i18n('Ymd\THis\Z',time(), true); echo PHP_EOL; ?>
SUMMARY:<?php echo $title; echo PHP_EOL; ?>
DESCRIPTION:<?php echo $description; echo PHP_EOL; ?>
LOCATION:<?php echo $location; echo PHP_EOL; ?>
TRANSP:OPAQUE
UID:<?php echo md5($settings['dce_calendar_title'].'-'.$element_id.'-'.$post_id); echo PHP_EOL; ?>
BEGIN:VALARM
ACTION:DISPLAY
TRIGGER;VALUE=DATE-TIME:<?php echo date_i18n('Ymd\\THi00\\Z',strtotime($start), true); echo PHP_EOL; ?>
DESCRIPTION:<?php echo $title; echo PHP_EOL; ?>
END:VALARM
END:VEVENT
END:VCALENDAR<?php
    //Collect output and echo
    $eventsical = ob_get_contents();
    ob_end_clean();
    echo $eventsical;
    exit();
} else {
    echo 'ERROR';
}
