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
		 * @param String $taxonomy
		 * @param Array $terms 
		 * Retrieves array of either category tag, or brafton_term id's to be included in article array
		 * @return $term_id[int] 
		 * 
		 */
		public function get_terms( $terms, $taxonomy, $video = null )
		{
			$term_array = array(); 
			if( isset( $terms ) ){ 
				foreach( $terms as $t )
					{
						if( isset( $video ) ){
							$t_id = $t->ListForArticle($brafton_id, 0, 100)->items[0]->id;
							$term_name = $t->Get( $t->id );	
						}
						else
							$term_name = $t->getName(); 

						$term_id = $this->insert_term( $term_name, $taxonomy );

						$term_array[] = $term_id;
					}
			}
			 
			$custom_terms = $this->get_custom_terms( $taxonomy );
			if( $custom_terms == false ) return $term_array;

			$include_custom = array_merge( $term_array, $custom_terms );
			return $include_custom;	
		}

		public function insert_term( $term_name, $taxonomy )
		{
			$term = get_term_by( 'name', sanitize_text_field( $term_name ), $taxonomy );
				//If term already exists	
				if( ! $term == false )
					$term_id = $term->term_id;
				//Insert new term
				else{
					// todo: check if term has a parent taxonomy.
					$term_id = wp_insert_term( sanitize_text_field( $term_name ), $taxonomy);
				}
				return $term_id;
		}
		/**
		 * Retrieves custom terms from options table. Returns Array of custom terms. 
		 * If no terms are defined, returns false.
		 * 
		 * @param Array $term_array
		 * @param String $taxonomy
		 * @return Mixed $custom_terms 
		 */
		public function get_custom_terms(  $taxonomy )
		{
			$option = 'brafton_custom_' . $taxonomy;
			$custom_terms = get_option( $option );
			
			if( $custom_terms == '' )
				return false;				

			$terms = explode( ' ', $custom_terms );
			foreach( $terms as $t )
			{
				$term_id = $this->insert_term( $t, $taxonomy );

				$term_array[] = $term_id;
			} 
			return $term_array;
		}

		/**
		 *  Retrieves parent term if given term has a parent in the feed. 
		 *  Only applies to cats not tags because tags don't support hierarchical taxonomy
		 *  @param $term_id 
		 *  @return $parent_term; 
		 */
		// function get_parent($term_array, $term_id)
		// {
		// 	 loop through all cats in the term_array;
		//   if given term has a perent;
		// 	return $parent_term;
		// }
	}	
?>