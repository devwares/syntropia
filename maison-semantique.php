<?php
/*
 * Plugin Name: syntropia
 * Plugin URI: https://gaia/
 * Description: This is a plugin which aims to emulate the default caracteristics of a Linked Data Platform compatible server
 * Version: 0.1
 * License: GPL2
 */

namespace syntropia;
 
// If the file is accessed outside of index.php (ie. directly), we just deny the access
defined('ABSPATH') or die("No script kiddies please!");

//require_once('includes.php');

class syntropia
{

	/* default constructor */
    public function __construct()
    {
	
		new syntropia_includes();
		
		/* calls a function to register routes at the Rest API initialisation */
        add_action('rest_api_init', array($this, 'syntropia_register_routes')) ;
        
    }


	/* Registers custom routes (comments for each route are listed above)
	 * 
	 * E.g : "yoursite.com/wordpress/wp-json/ldp/custom/" where
	 * 
	 * "yoursite.com/wordpress" is the main url
	 * "/wp-json/ is the default route for requests to the embedded WP rest api
	 * "/ldp" is the first URL segment after core prefix. Must be unique to our plugin
	 * "/custom" is the route to some function */
	 
	public function syntropia_register_routes()
    {

		syntropia_debug('syntropia_register_routes');
		
		/* Registers a route for listing posts */
		register_rest_route( 'syntropia', '/posts/', array(
		'methods' => 'GET',
		'callback' => array($this, 'syntropia_list_posts') ));
		
		/* Registers a route for fonction post_details */
		register_rest_route( 'syntropia', '/posts/(?P<slug>[a-zA-Z0-9-]+)', array(
		'methods' => 'GET',
		'callback' => array($this, 'syntropia_detail_post') ));

		/* Registers a route for fonction gpio_get */
		register_rest_route( 'syntropia', '/gpio/(?P<slug>[a-zA-Z0-9-]+)/', array(
		'methods' => 'GET',
		'callback' => array($this, 'syntropia_gpio_get') ));
		
		/* Registers a route for fonction gpio_post */
		register_rest_route( 'ldp', '/gpio/(?P<slug>[a-zA-Z0-9-]+)/', array(
		'methods' => 'POST',
		'callback' => array($this, 'syntropia_gpio_post') ));
		
	}
	
	/*
	 *  Returns all posts (in jdson-ld format ?)
	 * 	method : GET
	 * 	url : http://www.yoursite.com/wp-json/ldp/posts/
	 */
	 
	public function syntropia_list_posts()
	{	 
		
		syntropia_debug('syntropia_list_posts');
		
		// sets headers
		syntropia_default_headers();
		
		// lists all posts in array
		$tabPosts = get_posts();
		
		for ($cpt = 0; $cpt < count($tabPosts) ; $cpt++)
			{
				$posts[$cpt] = array(
				'rdfs:label'=>$tabPosts[$cpt]-> post_name,
				'dcterms:title'=>$tabPosts[$cpt]-> post_title,
				'dcterms:created'=>$tabPosts[$cpt]-> post_date,
				'sioc:User'=>$tabPosts[$cpt]-> post_author) ;
			}
		
		// initializes the "context" in array
		// see : http://json-ld.org/spec/latest/json-ld/#the-context
		$context = syntropia_get_context();
		
		// stores posts in array
		$graph = syntropia_get_container_graph($posts);
		
		// formats response
		$retour = array('@context' => $context, '@graph' => array($graph));
		
		// checks response then returns
		return rest_ensure_response($retour);
		
	}

	/* 
	 * Returns selected details of specified post (from postname)
	 * method : GET
	 * url : http://www.yoursite.com/wp-json/ldp/posts/some-post-slug/
	 */

	public function syntropia_detail_post($data)
	{
		syntropia_debug('syntropia_detail_post');
		
		// sets headers
		syntropia_default_headers();
		
		// gets slug from args
		$slug = $data['slug'];
	
		// gets post from its slug
		$post = get_page_by_path($data['slug'],OBJECT,'post');
		
		/* Autre solution :
		 * 
		 * $args = array(
		 * 'name'        => $slug,
		 * 'post_type'   => 'post',
		 * 'post_status' => 'publish',
		 * 'numberposts' => 1);
		 * 
		 * $post = get_posts($args)[0]; */
	
		// keeps only useful properties, link them to rdf <properties>, stores them in array
		$filteredPost = array(
		'sioc:User' => $post -> post_author,
		'dcterms:created' => $post -> post_date,
		'dcterms:text' => $post -> post_content,
		'dcterms:title' => $post -> post_title,
		'undefined:1' => $post -> post_status,
		'undefined:2' => $post -> comment_status,
		'rdfs:label' => $post -> post_name,
		'dcterms:modified' => $post -> post_modified,
		'undefined:3' => $post -> post_type);
	
		// initializes the "context" in array
		// see : http://json-ld.org/spec/latest/json-ld/#the-context
		$context = syntropia_get_context();
		
		// formats data
		$retour = array('@context' => $context, '@graph' => array($filteredPost));
		
		// returns json-ld formatted post
		return rest_ensure_response($retour);

	}

	
	/*
	 * allows people to write comment for a given post
	 * methods : POST, OPTIONS
	 * url : http://www.yoursite.com/wp-json/ldp/posts/some-post-slug/comments/
	 */
	
	public function syntropia_gpio_post($data)
	{
		
		/*
		 * parameters :
		 * 
		 * 'rdfs:label' (slug)
		 * 'sioc:user' (author)
		 * 'dcterms:text' (content)
		 */

		// declarations
		$missingData = false;

		// sets headers
		syntropia_default_headers();
		header('Access-Control-Allow-Origin:*', true);
		
		// gets objects
		$body = json_decode($data->get_body());
		$context = $body->{'@context'};
		$graph = $body->{'@graph'};
		
		// gets @graph number 0 entrie, stores in array
		$graph_0 = $graph[0];
		
		// gets post_id from slug
		$comment_post_id = syntropia_get_postid_by_slug($graph_0->{'http://www.w3.org/2000/01/rdf-schema#label'});
		// probleme : le JS traduit 'rdfs:label' par son URI
		// Ã©crire une fonction qui rÃ©cupÃ¨re les bons URI ? ou inclure ces derniers dans la prÃ©sente fonction ?
		syntropia_debug('id article : ' . $comment_post_id);

		// gets poster id
		// TODO : envisager une creation de user "Ã  la volÃ©e" selon sioc:user ou compte invitÃ©
		// Toute une rÃ©flexion Ã  faire sur la gestion des utilisateurs, pour les posts/comments "externes"
		$comment_user_id = 2;
		$tabUser = get_user_by('id', $comment_user_id);
				
		// gets user infos from id
		$comment_author = $tabUser->display_name;
		$comment_author_email = $tabUser->user_email;
		$comment_author_url = $tabUser->user_url;
		syntropia_debug('auteur : ' . $comment_author);
		
		// gets content of the comment
		// TODO : ATTENTION Ã  la validation des donnÃ©es ici (balises!)
		$comment_content = $graph_0->{'dcterms:text'};		
		syntropia_debug('contenu : ' . $comment_content);
		
		// sets various properties
		// TODO : a dÃ©finir
		$comment_type = '';
		$comment_parent = 0;
		
		// gets poster IP and HTTP_USER_AGENT
		$comment_author_IP = $_SERVER['REMOTE_ADDR'];
		$comment_agent = $_SERVER['HTTP_USER_AGENT'];
		
		// gets current time
		$time = current_time('mysql');
		
		// formats comment data
		$tabComment = array(
		'comment_post_ID' => $comment_post_id,
		'comment_author' => $comment_author,
		'comment_author_email' => $comment_author_email,
		'comment_author_url' => $comment_author_url,
		'comment_content' => $comment_content,
		'comment_type' => $comment_type,
		'comment_parent' => $comment_parent,
		'user_id' => $comment_user_id,
		'comment_author_IP' => $comment_author_IP,
		'comment_agent' => $comment_agent,
		'comment_date' => $time,
		'comment_approved' => 1,
		);
		
		// creates comment
		// TODO: validation des donnÃ©es etc.
		wp_insert_comment($tabComment);
	
		// ALTERNATE ENDING : print_r($data->get_body()); exit(0);
		return($data);

	}
	
}

new syntropia();

?>
