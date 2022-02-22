<?php

// https://github.com/davejamesmiller/laravel-breadcrumbs#defining-breadcrumbs

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('contact-us', function ($trail) {
    $trail->parent('home');
    $trail->push('Contact us', route('contact-us'));
});

Breadcrumbs::for('all-news', function ($trail, $text) {
    $trail->parent('home');
    $trail->push($text, route('all-news', $text));
});

Breadcrumbs::for('all-external-news', function ($trail, $text) {
    $trail->parent('home');
    $trail->push($text, route('all-external-news', $text));
});

Breadcrumbs::for('about', function ($trail, $text) {
    $trail->parent('home');
    $trail->push($text, route('about', $text));
});

Breadcrumbs::for('search-results', function ($trail, $search_text) {
    $trail->parent('home');
    $trail->push($search_text, route('search-results', $search_text));
});

Breadcrumbs::for('account-register', function ($trail, $register_step) {
    $trail->parent('home');
    $trail->push($register_step, route('account-register-details', $register_step));
});


Breadcrumbs::for('votes-by-category', function ($trail, $voteCategory) {
    $trail->parent('home');
    $trail->push($voteCategory->name, route('votes-by-category', $voteCategory));
});


//            {{ Breadcrumbs::render('vote_by_slug', $activeVote) }}
Breadcrumbs::for('vote_by_slug', function ($trail, $vote) {
    $voteCategory= $vote->voteCategory()->first();
    $trail->parent('home');
    $trail->push($voteCategory->name, route('votes-by-category', $voteCategory->slug));
    $trail->push($vote->name, route('vote_by_slug', $vote));
});

Breadcrumbs::for('tag_by_slug', function ($trail, $tag_name) {
    $trail->parent('home');
    $trail->push($tag_name, route('tag_by_slug', $tag_name));
});




Breadcrumbs::for('event', function ($trail, $event_title) {
    $trail->parent('home');
    $trail->push($event_title, route('event', $event_title));
});


Breadcrumbs::for('page-content-by-slug', function ($trail, $page_content_title) {
    $trail->parent('home');
    $trail->push($page_content_title, route('page-content-by-slug', $page_content_title));
});




Breadcrumbs::for('profile', function ($trail, $parent_page_label= '', $page_label= '') {
    $trail->parent('home');
    if ( !empty($parent_page_label) ) {
        $trail->push($parent_page_label, route('profile-view') );
    }
    if ( !empty($page_label) ) {
        $trail->push($page_label);
    }
    
});

Breadcrumbs::for('public_profile', function ($trail, $parent_page_label= '', $page_label= '') {
    $trail->parent('home');
    if ( !empty($parent_page_label) ) {
        $trail->push($parent_page_label, route('profile-view') );//public_profile
    }
    if ( !empty($page_label) ) {
        $trail->push($page_label);
    }

});


/*
// Home > About
Breadcrumbs::for('about', function ($trail) {
    $trail->parent('home');
    $trail->push('About', route('about'));
});

// Home > Blog
Breadcrumbs::for('blog', function ($trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});

// Home > Blog > [Category]
Breadcrumbs::for('category', function ($trail, $category) {
    $trail->parent('blog');
    $trail->push($category->title, route('category', $category->id));
});

// Home > Blog > [Category] > [Post]
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('category', $post->category);
    $trail->push($post->title, route('post', $post->id));
});*/