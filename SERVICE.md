Application services
--------------------
This module provides services dealing with authentication, identity and role management described below.
For common operations on models (like persisting, remove, retrieve) we use ZfcBase [Model service](https://github.com/ZF-Commons/ZfcBase/blob/master/src/ZfcBase/Service/ModelServiceAbstract.php).
In this list model services are highlighted by "(Model service)".
Model service provides this standard API:

* get(array $key) - returns model instance; service implements minimum array('priKey' => [primarykey]) key search. Some services extends this e.g. array('identityId' => [identity id])
* persist(array $data) - model data to persist; returns array('model' => $model)
* remove($priKey) - removes model by primary key
* getPaginator(array $params) - returns paginator; $params are used to instruct mapper what models to select, this depends on a mapper implementation


### KapitchiIdentity\Service\Auth

This service extends from [Zend\Authentication\AuthenticationService](https://github.com/zendframework/zf2/blob/master/library/Zend/Authentication/AuthenticationService.php) so it provides whole API as the parent class does.
It's extended to provide events and creates [authentication identity object](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Model/AuthIdentity.php) to be stored into a storage instead of authentication ID only.

New public methods:

* getLocalIdentityId() - helper method which returns users local identity id. If user is not logged in throws an exception.


### KapitchiIdentity\Service\IdentityRole (Model service)

This service is used to manage identity role relationship. Currently one role is supported only.

Public methods (plus model service methods):

* getCurrentRole() - returns current identity's role object - e.g. 'identity/111' which refers to identity 111 user role; if local identity id could not be resolved this will equal to whatever getCurrentStaticRole() returns.
* getCurrentStaticRole() - returns current identity's static role - this is what has been assigned to the identity e.g. 'user', 'admin' etc.


### KapitchiIdentity\Service\Identity (Model service)

Service for CRUD operations on Identity objects.


### KapitchiIdentity\Service\AuthCredential (Model service)

Service for CRUD operations on AuthIdentity objects. Manages username and password for identities.


### KapitchiIdentity\Service\Registration (Model service)

Registration service providing CRUD operations on Registration objects. It also provides way for (self) user registration.

Public methods (plus model service methods):

* register(array $data) - runs $service->persist(data) under 'self_registrator' user role (only with ZfcAcl plugin enabled)


### KapitchiIdentity\Service\IdentityRegistration (Model service)

Service for CRUD operations on IdentityRegistration objects.



