<?php
/**
 * @author Ali <techsupport@brafton.com>
 * Handles post tag and category assignment. Maintains parent child relationships
 * if they exist. Brafton Tech or will need to set these relationships manually. 
 * 
 * If necessary we can add a get_parent_term to dynamically insert taxonomy hierarchy 
 * found in the feed.
 */
	
	class Brafton_Taxonomy {
	

		/**
		 * Adds given tags, categories, or custom taxonomies to wordpress database.   
		 * @param $taxonomy
		 * @param $terms 
		 * Retrieves array of either category tag, or brafton_term id's to be included in article array
		 * @return $term_id[int] 
		 * 
		 */
		public function get_terms( $terms, $taxonomy )
		{
			$term_array = array(); 
			foreach( $terms as $t ){
				$term = get_term_by( 'name', sanitize_text_field( $t->getName() ), $taxonomy );
				//If term already exists	
				if( ! $term == false )
					$term_id = $term->term_id;
				//Insert new term
				else{
					// todo: check if term has a parent taxonomy.
					$term_id = wp_insert_term( sanitize_text_field( $t->getName() ), $taxonomy);
				}

				$term_array[] = $term_id;
			} 
			return $term_array;
		}


		public function get_video_terms( $terms, $taxonomy ){
			$term_array = array(); 
			foreach( $terms as $t ){
				$term_name = $t->Get( $t->id );
				$term = get_term_by( 'name', sanitize_text_field( $term_name ), $taxonomy );
				//If term already exists	
				if( ! $term == false )
					$term_id = $term->term_id;
				//Insert new term
				else
					$term_id = wp_insert_term( sanitize_text_field( $term_name ), $taxonomy);

				$term_array[] = $term_id;
			} 

			return $term_array;
		}

		/**
		 * 
		 */
		function get_custom_term( $customTerm )
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