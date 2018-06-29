<?php
$tax = 'categorias-faqs';

$args = [
	'orderby' => 'name'
];

$archiveTerm = get_term_by( 'slug', get_query_var( 'term' ), $tax );

if ($archiveTerm){
	$args['include'] = $archiveTerm->term_id;
}else{
	$args['parent'] = 0;
}

$tax_terms = get_terms( $tax, $args);

echo NitFAQ\CPT\FAQsCPT::printFaqs($tax_terms, $tax)?>