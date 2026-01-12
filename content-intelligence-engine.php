<?php
/**
 * Plugin Name: Content Intelligence Engine
 * Description: Demonstrates WordPress Filter Hooks for real-time content analysis.
 * Version: 1.0
 * Author: [Your Name]
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Content_Intelligence_Engine
 * Handles the interception and analysis of post content using WordPress Filters.
 */
class Content_Intelligence_Engine {

    public function __construct() {
        // Intercepting the content before it is rendered to the browser
        add_filter( 'the_content', array( $this, 'process_content_analytics' ) );
    }

    /**
     * Logic: Calculates estimated reading time and word count.
     * Assumes an average reading speed of 200 words per minute.
     */
    private function get_content_metrics( $content ) {
        $plain_text = strip_tags( $content );
        $word_count = str_word_count( $plain_text );
        $reading_time = ceil( $word_count / 200 );

        return array(
            'count'   => $word_count,
            'minutes' => $reading_time
        );
    }

    /**
     * Controller: Appends the calculated metrics to the content display.
     */
    public function process_content_analytics( $content ) {
        // Ensure the logic only runs on single post entries to save resources
        if ( ! is_single() || ! is_main_query() ) {
            return $content;
        }

        $metrics = $this->get_content_metrics( $content );

        // Formatting the analytical block
        $analytics_header = '<section class="content-metrics" style="border: 1px solid #ddd; padding: 10px; margin-bottom: 20px; font-family: sans-serif;">';
        $analytics_header .= '<strong>Post Statistics:</strong> ';
        $analytics_header .= esc_html( $metrics['count'] ) . ' words | ';
        $analytics_header .= 'Estimated reading time: ' . esc_html( $metrics['minutes'] ) . ' minute(s)';
        $analytics_header .= '</section>';

        return $analytics_header . $content;
    }
}

// Initialize the engine
new Content_Intelligence_Engine();
