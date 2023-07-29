<?
CModule::AddAutoloadClasses(
	'cosmo.api',
	array(
		'Cosmo\\Api\\Main' => 'lib/main.php',
		'Cosmo\\Api\\Token\\Base' => 'lib/token/base.php',
		'Cosmo\\Api\\Token\\JWT' => 'lib/token/jwt.php',
		'Cosmo\\Api\\Events\\EventHandlers' => 'lib/events/eventHandlers.php',
		'Cosmo\\Api\\Events\\Functions' => 'lib/events/functions.php',
		'Cosmo\\Api\\V1\\Pagination' => 'lib/v1/pagination.php',
		'Cosmo\\Api\\V1\\Controllers\\UsersController' => 'lib/v1/controllers/usersController.php',
		'Cosmo\\Api\\V1\\Controllers\\ProductsController' => 'lib/v1/controllers/productsController.php',
		'Cosmo\\Api\\V1\\ActionFilter\\AuthenticationToken' => 'lib/v1/actionfilter/authenticationToken.php',
	)
);
?>