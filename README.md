# base-app-2
Is a PHP framework that offers to use VueJs as frontend single site rendering.


Install
-------
    php composer create-project opoink/opoink-app-2
    
If you dont have composer istalled on your machine, you can download composer.phar

    wget https://getcomposer.org/composer.phar

Then do this command on your terminal

    php composer.phar create-project opoink/opoink-app-2

cd to <intallation dir>/App/node directory to run npm command


Generate Component
-------
    php opoink --generate=component --location=Vendor_Module::pages/home --component-name=my-component
    php opoink --g=c --location=Vendor_Module::pages/home --cn=my-component
    php opoink --g=c --l=Vendor_Module::pages/home --cn=my-component
    php opoink --g=c --l=Vendor_Module --cn=cmy-component


we still use Opoink CLI to generate VueJs component
--generate or --g: the action to do.
--location or --l: is the location where to generate the component 


Component injection
-------
With the help of jQuery Opoink can inject your component to the exisitng component. either of the same module or another exisitng module.
Simply means that you don't have to make any changes from your other module component, You just have to tell opoink where do you want to inject you new component, and let opoink to do the job for you. 

    {
        "component_name": "MyComponent",
        "vendor": "Vendorname",
        "module": "Modulename",
        "location": "Vendorname\/Modulename\/vue\/components\/pages\/home\/MyComponent\/MyComponent.component",
        "inject_to": [
            {
                "component_name": "ExistingComponent",
                "element_id": "element-id-from-the-component-template",
                "inject_type": "prepend",
                "wrapper": "li"
            }
        ]
    }
