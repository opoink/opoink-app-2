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

inject_to: is optional means that opoink should inject this component to another with the given name
component_name: required if inject to is decalred
element_id: optional if has value opoink will try to look for this element, if the element is found then use this as reference for the injection, if not your component will be injected either at the top or at the botom of your component template
inject_type: before, after, append, or prepend
    before: your component will be injected before the element
    after: your component will be injected after the element
    append: your component will be injected at the bottom of your component element
    prepend: your component will be injected at the top of your component element
