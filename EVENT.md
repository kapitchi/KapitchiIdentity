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

