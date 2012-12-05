<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "browse/search";
$route['404_override'] = '';

$route['add_new_entity'] = "browse/entity/AddNewEntity";

$route['logout'] = "authorize/logout";
$route['login'] = "authorize/login";

$route['browse/edit_entity/(:any)'] = "browse/entity/editEntity/$1";
$route['browse/delete_entity/(:any)'] = "browse/entity/deleteEntity/$1";
$route['browse/delete_entity_name'] = "browse/entity/deleteEntityName";
$route['browse/edit_entity_name/(:any)'] = "browse/entity/editEntityName/$1";

$route['browse/entity/'] = "browse";
$route['browse/entity'] = "browse";
$route['browse/name_documents/'] = "browse";
$route['browse/name_documents'] = "browse";
$route['browse/entity/(:any)'] = "browse/entity/showEntityData/$1";
$route['browse/entity_add_alt_name/(:any)'] = "browse/entity/addAltName/$1";
$route['browse/entity_add_ontology/(:any)'] = "browse/entity/addOntEntity/$1";
$route['browse/entity_add_resource/(:any)'] = "browse/entity/addRes/$1";
$route['browse/name_documents/(:any)'] = "browse/name_documents/showNameDocumentData/$1";
$route['browse/add_document_to_name/(:any)'] = "browse/name_documents/addNameDocument/$1";

$route['browse/edit_document/(:any)'] = "browse/name_documents/editDocument/$1";
$route['browse/delete_name_document'] = "browse/name_documents/deleteNameDocument";

$route['browse/delete_ontology'] = "browse/entity/deleteOntology";
$route['browse/edit_entity_ontology'] = "browse/entity/editOntology";

$route['browse/delete_entity_resource'] = "browse/entity/deleteResource";
$route['browse/edit_entity_resource'] = "browse/entity/editResource";

$route['index'] = "rdf_xml/showAllNames";
$route['names'] = "rdf_xml/showAllNames";

$route['entities'] = "rdf_xml/showAllEntities";

$route['search/(:any)'] = "rdf_xml/showSearchedNames/$1";
$route['search'] = "rdf_xml/showSearchedNames";
$route['search/'] = "rdf_xml/showSearchedNames/";

$route['entity/'] = "rdf_xml/showEntityData/";
$route['entity'] = "rdf_xml/showEntityData";
$route['entity/(:any)'] = "rdf_xml/showEntityData/$1";

$route['name/'] = "rdf_xml/showNameData/";
$route['name'] = "rdf_xml/showNameData";
$route['name/(:any)'] = "rdf_xml/showNameData/$1";

$route['stats'] = "rdf_xml/showStats";
$route['time_dict/(:any)'] = "rdf_xml/timeDict/$1";
$route['time_dict/(:any)/(:num)'] = "rdf_xml/timeDict/$1/$2";
/* End of file routes.php */
/* Location: ./application/config/routes.php */