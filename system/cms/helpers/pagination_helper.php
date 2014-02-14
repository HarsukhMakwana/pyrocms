<?php defined('BASEPATH') OR exit('No direct script access allowed.');

/**
 * PyroCMS Pagination Helpers
 *
 * @package PyroCMS\Core\Helpers
 * @author      PyroCMS Dev Team
 * @copyright   Copyright (c) 2012, PyroCMS LLC
 */
if ( ! function_exists('create_pagination')) {

	/**
	 * The Pagination helper cuts out some of the bumf of normal pagination
	 *
	 * @param string $uri The current URI.
	 * @param int $total_rows The total of the items to paginate.
	 * @param int|null $limit How many to show at a time.
	 * @param int $uri_segment The current page.
	 * @param boolean $full_tag_wrap Option for the Pagination::create_links()
	 * @return array The pagination array.
	 * @see Pagination::create_links()
	 */
	function create_pagination($uri, $total_rows, $limit = null, $uri_segment = 4, $full_tag_wrap = true)
	{
		$ci = & get_instance();
		$ci->load->library('pagination');

		$current_page = $ci->input->get('page') ? $ci->input->get('page') : 1;
		$suffix = $ci->config->item('url_suffix');

		$limit = $limit === null ? Settings::get('records_per_page') : $limit;

		// Initialize pagination
		$ci->pagination->initialize(array(
			'suffix' 				=> $suffix,
			'base_url' 				=> ( ! $suffix) ? rtrim(site_url($uri), $suffix) : site_url($uri),
			'total_rows' 			=> $total_rows,
			'per_page' 				=> $limit,
			'uri_segment' 			=> $uri_segment,
			'use_page_numbers'		=> true,
			'reuse_query_string' 	=> true,
		));

		$offset = $limit * ($current_page - 1);

		//avoid having a negative offset
		if ($offset < 0) $offset = 0;

		return array(
			'current_page' => $current_page,
			'total_pages' => ceil($total_rows/$limit),
			'per_page' => $limit,
			'limit' => $limit,
            'total' => $total_rows,
			'offset' => $offset,
			'links' => $ci->pagination->create_links($full_tag_wrap)
		);
	}
}
