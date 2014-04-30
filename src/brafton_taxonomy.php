<?php
/**
 * Handles post tag and category assignment. Maintains parent child relationships
 * if they exist. You'll need to set these relationships manually. 
 */
	
	class Brafton_Taxonomy {
	
		/**
		 * @param $brafton_id 
		 * Retrieves array of either category tag, or brafton_term id's to be included in article array
		 * @return $term_id[int] 
		 * 
		 */
		function get_terms( $terms, $taxonomy )
		{
			$term_array = array(); 
			foreach $terms as $t {

				$term = get_term_by( 'name', sanitize_text_field($t), $taxonomy );
				
				if( ! $term == false )
					$term_id = $term->term_id;
				else
					$term_id = wp_insert_term( $t, $taxonomy);

				$term_array[] = $term_id;
			} 
			return $term_array;
		}

		/**
		 * 
		 */
		function get_custom_term( brafton_id, $customTerm )
		{
			#todo: 
		}

		/**
		 *  Retrieves parent term if given term has a parent. 
		 *  @param $term_id 
		 *  @return $parent_term; 
		 */
		// function get_parent($term_id)
		// {
		// 	#might not be necessary if get term by name look up maintains
		// 	#parent child relationships.
		// 	$child_term = get_term( $term_id, $this->taxonomy );
		// 	$parent_term = get_term( $child_term->parent, $this->taxonomy );
		// 	return $parent_term;
		// }
	}	
?>