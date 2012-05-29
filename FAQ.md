Use cases
=========

This section tries to cover common use cases you might want to do or extend KapitchiIdentity module by.
See ["Options" section](https://github.com/kapitchi/KapitchiIdentity/blob/master/README.md) first for all avaliable options which are shipped with the module directly - the customization you need might have been implemented!


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


Custom redirection of users after log
-------------------------------------

"I want user to be redirect to my custom page after they log in"

### Rationale
You need to hook into login controller login.post event and return Response object with the redirect.

### Implementation

See: [AuthController::loginPost() default listener](https://github.com/kapitchi/KapitchiIdentity/blob/master/src/KapitchiIdentity/Controller/AuthController.php)


Changing registration page template script
------------------------------------------

"I don't like how registration page renders and want to use my custom template script for it"

### Rationale
You need to hook into registration controller register.pre event and change template on registration view model.

### Implementation
This might be example of what you need to do:

```
$sharedEventManager->attach('KapitchiIdentity\Controller\RegistrationController', 'register.pre', function(Event $e) {
    $e->getParam('viewModel')->setTemplate('mymodule/customregistration');
});
```

