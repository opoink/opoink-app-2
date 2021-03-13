# base-app-2
Is a PHP framework that offers to use VueJs as frontend single site rendering.


Install
-------
    php composer create-project opoink/opoink-app-2
    
If you dont have composer installed on your machine, you can download composer.phar

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
1. --generate or --g: the action to do.
2. --location or --l: is the location where to generate the component
3. --component-name or --cn: your new component name alpha, - and _ are the accepted characters. - _ is just a separator

This will generate a new directory and four files under **/App/Ext/Vendor/Module/View/vue/components**

    MyComponent.ts
    MyComponent.component.ts
    MyComponent.html
    MyComponent.scss

This will also generate **vue.components.ts** and **components.json** under **/App/Ext/Vendor/Module** if this two files exists opoink will update the file to include the newly generated component.



Component Injection
-------
With the help of jQuery Opoink can inject your component to the existing component. either of the same module or another existing module.
Simply means that you don't have to make any changes from your other module component, You just have to tell opoink where do you want to inject your new component, and let opoink to do it for you. 

in **components.json** file add **inject_to** to the component

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
                "wrapper": "div"
            }
        ]
    }

1. **inject_to**: is optional means that opoink should inject this component to another with the given name
2. **component_name**: required if **inject_to** is declared
3. **element_id**: optional if has value opoink will try to look for this element, if the element is found then use this as reference for the injection, if not your component will be injected either at the top or at the botom of your component template
4. **inject_type**: before, after, append, or prepend
    1. **before**: your component will be injected before the element
    2. **after**: your component will be injected after the element
    3. **append**: your component will be injected at the bottom of your component element
    4. **prepend**: your component will be injected at the top of your component element
5. **wrapper**: optional, your component will be wrapped inside this markup then inject.


Router
-------
vue.routes.ts under your Vendor/Module dir

    import VRouter from './../../../node/src/core/VueRouter';

    const HomeComponent = () => import(/* webpackChunkName: "HomeComponent" */ './View/vue/components/pages/Home/Home.component');
    const LoginComponent = () => import(/* webpackChunkName: "LoginComponent" */ './View/vue/components/pages/Login/Login.component');

    let routes = [
        { path: '/', component: HomeComponent },
        { path: '/login', component: LoginComponent }
    ]

    routes.forEach(route => {
        VRouter.addRoute(route);
    });
