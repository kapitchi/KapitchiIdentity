Zend Framework 2 - Kapitchi Identity module
=================================================
Version: 0.1
Author:  Matus Zeman

Introduction
============
Provides authentication, identity and role management. When we say _identity_ we refer to ordinary human users or other 3rd party applications interacting with your system.


Features
========

* Authentication
  * Credential ([AuthStrategy\Credential plugin](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Plugin/AuthStrategy/Credential.php)) [COMPLETE]
  * OAuth2 [IN PROGRESS]
  * HTTP Basic/Digest [IN PROGRESS]
  * OpenID [NOT STARTED]
  * Facebook Connect [NOT STARTED]
  * LDAP [NOT STARTED]
* Registration 
  * User name/password form ([RegistrationAuthCredential plugin](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Plugin/RegistrationAuthCredential.php)) [COMPLETED]
  * Email registration/login ([AuthCredentialEmail plugin](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Plugin/AuthCredentialEmail.php)) [COMPLETE]
  * Email validation ([AuthCredentialEmailValidation plugin](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Plugin/AuthCredentialEmailValidation.php)) [IN PROGRESS]
* Identity management [IN PROGRESS]
* Identity - Role management ([IdentityRole plugin](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Plugin/IdentityRole.php)) [COMPLETED]
* Role management [NOT STARTED]
* Password recovery ([AuthCredentialForgotPassword plugin](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Plugin/AuthCredentialForgotPassword.php)) [IN PROGRESS]


Requirements
============

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)
* [KapitchiBase](https://github.com/matuszemi/KapitchiBase) (latest master)
* [ZfcBase](https://github.com/ZF-Commons/ZfcBase) (latest master)
* [ZfcAcl](https://github.com/ZF-Commons/ZfcAcl) (optionally) (latest master)


Installation
============
1. Put the module into /vendor folder and activate it in application.config.php.
2. Create DB tables - deploy/mysql_install.sql
3. Make sure you go through _Configuration_ section
4. Go to http://yourpublicfolder/KapitchiIdentity/auth/login in order to login


Configuration
-------------

### DB settings
This module depends on Zend\Db\Adapter\Adapter. By default it uses tries to use 'Zend\Db\Adapter\Adapter' Di instance so make sure it is set as Di instance
or you have been using another one you can still overwrite it by putting following code into e.g. /config/autoload/module.KapitchiIdentity.config.php.

```
return array(
    'di' => array(
        'alias' => array(
            'KapitchiIdentity-db_adapter' => 'MyDbAdapter',
        ),
    ),
);
```

### "Root" login
Root login is automatically enabled so you can login and start using your application with full permissions. Root user is the only user which is created 

```
return array(
    'KapitchiIdentity' => array(
        'plugins' => array(
            'AuthStrategyRoot' => array(
                'options' => array(
                    'password' => '21232f297a57a5a743894a0e4a801fc3'// md5 hash of 'admin'
                )
            ),
        )
    ),
);
```



Use cases
=========

This section tries to cover common use cases you might want to do or extend KapitchiIdentity module by.

Extending registration
----------------------

"I want to add new fields into registration form and store them into my table"

### Rationale
You need to hook into registration form construct.post event in order to add new form fields i.e. add extension sub-form.
To retrieve and store form data to your persistence storage attach listener to registration service persist.post event.

### Implementation
Easiest (we also believe cleanest) way how to achieve the above is to implement RegistrationMyExtension plugin in your module. Example might be seen in [RegistrationAuthCredential plugin](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Plugin/RegistrationAuthCredential.php) or [Contact registration plugin](https://github.com/kapitchi/KapitchiContactIdentity/blob/master/src/KapitchiContactIdentity/Plugin/Registration.php) which adds basic contact fields to the registration process.

If you copy & paste RegistrationAuthCredential implementation make sure you rename $extName property to "[YourModuleNamespace]_[YourExtension]" e.g. "MyModule_UsefulExtension" so it will not conflict with other modules.
Please notice $modelServiceClass and $modelFormClass properties - these define what service and form to hook up on.  

Method getForm() should return your form implementation and persistModel(ModelAbstract $model, array $data, $extData) should be responsible for persisting your data.
Parameter $model contains Registration model with Identity model "$identity = $model->ext('Identity')". Array of your form values should be find in $extData parameter.
You can keep getModel() and removeModel() methods empty if you don't want your data being deleted whenever registration data/model would be. 


Changing registration page template script
------------------------------------------

"I don't like how registration page renders and want to use my custom template script for it"

### Rationale
You need to hook into registration controller register.pre event and change template on registration view model.

### Implementation
This might be example of what you need to do:

```
$events = StaticEventManager::getInstance();
$events->attach('KapitchiIdentity\Controller\RegistrationController', 'register.pre', function(Event $e) {
    $e->getParam('viewModel')->setTemplate('mymodule/customregistration');
});
```


Usage
=====


Options
-------
See [module config](https://github.com/kapitchi/KapitchiIdentity/blob/master/config/module.config.php#L4) for all options available.


Authentication strategy plugins
-------------------------------
Authentication of the user might be more complex operation then just submit a form with user name and password. That is why we introduced concept of authentication strategy.
Good example might be authentication using OpenID where the process also includes redirecting a user to OpenID provider.  
A strategy in general wraps all necessary functionality into one place and is responsible for:

* adding relevant form elements into login form
* deciding whether it is ready to authenticate
* initializing authentication adapter and performing authentication (strategy itself is an authentication adapter)
* resolving authenticated user into system user

Every authentication strategy needs different data to be entered by the user. Standard credential auth needs user name and password, OpenID needs one field - OpenID identifier.
A strategy is responsible for adding these fields into login form and validating them when the form is submitted.

If the strategy finds out the form (a strategy checks only fields/subform it has added) is valid i.e. user provided both user name and password it triggers an authentication on the adapter a strategy provides.

Once an user is authenticated successfully Auth service checks if strategy is able to provide local identity ID. In order to recognize this a strategy needs to implement [AuthIdentityResolver interface](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Service/AuthIdentityResolver.php).
If it does so, the service asks the strategy to resolve authenticated ID to [AuthIdentity object](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Model/AuthIdentity.php) with local identity id.
Otherwise generic AuthIdentity with _auth_ role is created by default with no local identity id set.

### Implementing new authentication strategies

Implementing [abstract authentication strategy plugin](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Plugin/AuthStrategy/StrategyAbstract.php) is most convenient way how you can implement new strategy.
By doing so you automatically get access to auth controller, request, response objects, login form and view model.



Roles
-----

Responsibility of this module is to provide role of an user also. At the moment only one role can be assigned to an identity/user.
A role should be known from local identity id (if authentication strategy is able to resolve it as discussed above - Authentication strategies).

A strategy is not required to resolve local identity id. Example might be Facebook Connect strategy which is used to authenticate Facebook users to the site only.
Such strategy can set 'facebook' role for the user. This can be then used to help an application to decide (usign ZfcAcl module) if an user can see certain (social/facebook) blocks on the page.

TODO - roles used, static/identity roles, registration - selfregistrator


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

* register(array $data) - runs $service->persist(data) under 'selfregistrator' user role.


### KapitchiIdentity\Service\IdentityRegistration (Model service)

Service for CRUD operations on IdentityRegistration objects.



Events
------

### KapitchiIdentity\Auth\RegistrationController:register.pre

This event can be used to change registration page template.

Parameters:

* viewModel - [Register view model](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/View/Model/RegistrationRegister.php) object

### KapitchiIdentity\Auth\RegistrationController:register.post

It is trigger after successful registration. Hook into this event to e.g. overwrite default redirect to 'KapitchiIdentity/Auth/Login' route.

Parameters:

* viewModel - [Register view model](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/View/Model/RegistrationRegister.php) object
* registerResult - [KapitchiIdentity\Service\Registration::register()](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Service/Registration.php) result


### KapitchiIdentity\Auth\AuthController:login.pre

This event can be used to change login page template or add new fields into login form which can be retrieve from AuthLogin view model - usage of 'KapitchiIdentity\Form\Login:construct.post' event can be used also.
It can be also used by modules to lock out login e.g. to certain IP addresses on a black list.  
Request and response objects can be obtained from controller object (event target).

Parameters:

* viewModel - [Login view model](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/View/Model/AuthLogin.php) object



### KapitchiIdentity\Auth\AuthController:login.auth

Event used to validate form fields by a strategy and recognize if it is ready to authenticate - a strategy returns itself or auth adapter itself.
This event can also be used to redirect user (e.g. OpenID) when returning Response object or respond with Auth request response in case of Http adapter.  
Request and response objects can be obtained from controller object (event target).

Triggers until: Zend\Authentication\Adapter || Zend\Stdlib\ResponseDescription

Parameters:

* viewModel - [Login view model](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/View/Model/AuthLogin.php) object


### KapitchiIdentity\Auth\AuthController:login.post

Triggered after we try to authenticate - either successfully or failed. This can be also used to redirect user somewhere. By default it redirects to _KapitchiIdentity/Identity/Me_ route.  
Request and response objects can be obtained from controller object (event target).

Parameters:

* result - [Authentication result](https://github.com/zendframework/zf2/blob/master/library/Zend/Authentication/Result.php)
* adapter - Authentication adapter/strategy being used


### KapitchiIdentity\Auth\AuthController:logout.post

Triggered after we call AuthService::clearIdentity(). Can be used to redirect user somewhere. By default it redirects to _KapitchiIdentity/Auth/Login_ route.  
Request and response objects can be obtained from controller object (event target).

Parameters:

* authIdentity - [Auth identity](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Model/AuthIdentity.php) object


### KapitchiIdentity\Service\Auth:authenticate.valid

Event is trigger after user has successfully authenticated but before their auth identity is stored into auth storage.

Parameters:

* result - authentication result
* adapter - authentication adapter/strategy
* authIdentity - [Auth identity](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Model/AuthIdentity.php) object



### KapitchiIdentity\Service\Auth:clearIdentity.post

Triggered after we clear auth storage.

Parameters:

* authIdentity - [Auth identity](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Model/AuthIdentity.php) object



Model service events
---------------------

Events below are trigger by [ModelServiceAbstract](https://github.com/ZF-Commons/ZfcBase/blob/master/src/ZfcBase/Service/ModelServiceAbstract.php) class.
Most of KapitchiIdenity service classes extend from this "base" class.

### [MODEL_SERVICE]:get.load

Used to load model instance. You can attach your custom listeners to provide different ways how a model loads.

Triggers until: [MODEL_PROTOTYPE]

Parameters: array parameter passed to get(filter) method

* priKey
* ...

Example might be (model service).

```
    protected function attachDefaultListeners() {
        parent::attachDefaultListeners();
        
        $mapper = $this->getMapper();
        $this->events()->attach('get.load', function($e) use ($mapper){
            $filter = $e->getParam('identityId');
            if(!$filter) {
                return;
            }
            return $mapper->findByIdentityId($filter);
        });
    }
```

### [MODEL_SERVICE]:get.exts
Triggered after a model has been loaded and to inform other plugins/modules to load all extensions available for this model.
Listeners should use $model->ext($myExtensionModel, 'MyExtension').

Parameters:

* model - Model loaded

### [MODEL_SERVICE]:get.ext.[EXTENSION_NAME]
Triggered after a model has been loaded and to inform other plugins/modules to load [EXTENSION_NAME] model extension.
Listeners should use $model->ext($myExtensionModel, [EXTENSION_NAME]).

Parameters:

* model - Model loaded

### [MODEL_SERVICE]:get.post

Parameters:

* model - Model loaded

### [MODEL_SERVICE]:persist.pre

TODO

### [MODEL_SERVICE]:persist.post

TODO

### [MODEL_SERVICE]:remove.pre

TODO

### [MODEL_SERVICE]:remove.post

TODO

