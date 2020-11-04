<?php

add_action('rest_api_init', 'universityRegisterSearch');

function universityRegisterSearch() {
  register_rest_route('university/v1', 'search', [
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'universitySearchResults'
  ]);
}

function universitySearchResults() {
  $professors = new WP_Query([
      'post_type' => 'professor'
  ]);
  
  // populating an array with only the data we want
  $professorResults = [];

  while( $professors->have_posts()) {
    $professors->the_post();
    array_push( $professorResults, [
        'title' => get_the_title(),
        'permalink' => get_the_permalink()
    ]);
  }

  return $professorResults;
}