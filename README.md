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
  * OAuth2 - Google, Facebook [IN PROGRESS]
  * HTTP Basic/Digest [IN PROGRESS]
  * OpenID [NOT STARTED]
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

Copy [KapitchiIdentity.global.config.php](https://github.com/kapitchi/KapitchiIdentity/blob/master/deploy/KapitchiIdentity.global.config.php) to application /config/autoload folder and modify options as required.

### DB settings
This module depends on Zend\Db\Adapter\Adapter. By default it tries to use 'Zend\Db\Adapter\Adapter' Di instance so make sure it is set as Di instance or overwrite it in KapitchiIdentity.global.config.php file.

```
File: /config/autoload/local.config.php

return array(
    'di' => array(
        'instance' => array(
            'Zend\Db\Adapter\Adapter' => array(
                'parameters' => array(
                    'driver' => array(
                        'driver' => 'Pdo',
                        'username' => 'root',
                        'password' => '',
                        'dsn'   => 'mysql:dbname=kapitchi;hostname=localhost',
                    ),
                ),
            ),
        ),
    ),
);
```

### "Root" login
Root login is automatically enabled so you can login and start using your application with no restrictions (when using with ZfcAcl module).
Root user is the only user which is created when import mysql_install.sql. You create additional users by registering or creating them manually on "Manage identities" page.  
See [KapitchiIdentity.global.config.php](https://github.com/kapitchi/KapitchiIdentity/blob/master/deploy/KapitchiIdentity.global.config.php) for available options.

Note: (ZfcAcl) Root user has set all privileges on all resources. Although when you want to grand access to a resource this resource has to be properly registered in Acl first otherwise "isAllow" will resolve to false.
See [ZfcAcl module](https://github.com/ZF-Commons/ZfcAcl) for more details.


Usage
=====

Options
-------
See [module config](https://github.com/kapitchi/KapitchiIdentity/blob/master/config/module.config.php#L4) for all options available.


Default routes/pages
--------------------
Manage identities - /KapitchiIdentity/identity/index
Create identity - /KapitchiIdentity/identity/create
Register - /KapitchiIdentity/registration/register
Login - /KapitchiIdentity/auth/login
Logout - /KapitchiIdentity/auth/logout


Use cases
=========

This section tries to cover common use cases you might want to do or extend KapitchiIdentity module by.
See "Options" section first for all avaliable options which are shipped with the module directly - the customization you need might have been implemented!

See [FAQ.md](https://github.com/kapitchi/KapitchiIdentity/blob/master/FAQ.md)


Application services
====================
See [SERVICE.md](https://github.com/kapitchi/KapitchiIdentity/blob/master/SERVICE.md).

Events
======
See [EVENT.md](https://github.com/kapitchi/KapitchiIdentity/blob/master/EVENT.md).


Design and implementation
=========================

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

TODO

Roles
-----

Responsibility of this module is to provide role of an user also. At the moment only one role can be assigned to an identity/user.
A role should be known from local identity id (if authentication strategy is able to resolve it as discussed above - Authentication strategies).

A strategy is not required to resolve local identity id. Example might be Facebook Connect strategy which is used to authenticate Facebook users to the site only.
Such strategy can set 'facebook' role for the user. This can be then used to help an application to decide (usign ZfcAcl module) if an user can see certain (social/facebook) blocks on the page.

TODO - roles used, static/identity roles, registration - selfregistrator


