Zend Framework 2 - Kapitchi Identity module
=================================================
Version: 0.1
Author:  Matus Zeman

Introduction
============
Provides authentication and identity management. When we say _identity_ we refer to ordinary human users or other 3rd party applications interacting your system.


Features
========

* Authentication
  * Credential [COMPLETE]
  * OpenID [IN PROGRESS]
  * HTTP Basic/Digest [IN PROGRESS]
  * Facebook Connect [NOT STARTED]
  * LDAP [NOT STARTED]
* Identity management [IN PROGRESS]
* Identity - Role management [NOT STARTED]


Requirements
============

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)
* [KapitchiBase](https://github.com/matuszemi/KapitchiBase) (latest master)
* [ZfcAcl](https://github.com/ZF-Commons/ZfcAcl) (optionally) (latest master)


Usage
=====


Options
-------
See [module config](https://github.com/kapitchi/KapitchiIdentity/blob/master/config/module.config.php#L4) for all options available.

Application services
--------------------

### KapitchiIdentity\Service\Auth

This service extends from Zend\Authentication\AuthenticationService so provides whole API as Zend class does.
It's extended to provide events and creates [authentication identity object](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Model/AuthIdentity.php) to be stored into a storage instead of authentication ID.

New public methods (against [Zend\Authentication\AuthenticationService](https://github.com/zendframework/zf2/blob/master/library/Zend/Authentication/AuthenticationService.php)):

* getLocalIdentityId() - helper method which returns users local identity id. If user is not logged in throws an exception.

### KapitchiIdentity\Service\Identity

Service for CRUD operations on Identity objects.

Authentication strategies
-------------------------
Authentication of the user might be more complex operation then just submit a form with user name and password. That is why we introduced concept of authentication strategy.
Good example might be authentication using OpenID where the process also includes redirecting a user to OpenID provider.  
A strategy in general wraps all necessary functionality into one place and is responsible for:

* adding relevant form elements into login form
* deciding whether it is ready to authenticate
* initializing authentication adapter and performing authentication (strategy itself is an authentication adapter)
* resolving authenticated user into system user

Every authentication strategy needs different data to be entered by the user. Standard credential auth needs user name and password, OpenID needs one field - OpenID identifier.
A strategy is responsible for adding these fields into login form and validating them when the form is submitted.

If the strategy finds out the form (a strategy checks only fields/subform it has added) is valid i.e. user provided both user name and password it returns authentication adapter (itself).
This will trigger an authentication on the adapter a strategy provides.

Once an user is authenticated successfully Auth service checks if strategy is able to provide local identity ID. In order to recognize this a strategy needs to implement AuthIdentityResolver interface.

Events
------

### KapitchiIdentity\Auth\AuthController.login.pre

This event can be used to add new fields into login form which can be retrieve from AuthLogin view model.
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

Parameters:

* result - authentication result
* adapter - authentication adapter/strategy
* authIdentity - [Auth identity](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Model/AuthIdentity.php) object



### KapitchiIdentity\Service\Auth:clearIdentity.post

Triggered after we clear auth storage.

Parameters:

* authIdentity - [Auth identity](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Model/AuthIdentity.php) object




