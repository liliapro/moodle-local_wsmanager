# Moodle WSManager Local Plugin

[![License](https://poser.pugx.org/covex-nn/moodle/license)](http://www.gnu.org/copyleft/gpl.html)

Web-Services allows to integrate Moodle data with another external applications and programming languages through XML or
JSON formats. For example, you can export or import Moodle users data with your CRM. In other words, this is an API:
REST or SOAP.

`moodle-local_wsmanager` lets you easily manage and
test [Moodle Web Services](https://docs.moodle.org/403/en/Using_web_services) instances from one place and much more.

Contents
========

* [Why?](#why)
* [Installation](#installation)
* [Overview](#overview)
* [Usage](#usage)
* [Dashboard](#dashboard)
* [Web Services](#web-services)
* [Test/execute functions](#testexecute-functions)
* [Credits](#credits)
* [License](#license)

### Why?
---

Creating Moodle Web-Services and tuning up the ones is a huge job if you are following a "Moodle way": administrator has
to jump between a lot of sections. **WSManager** has only two sections:

* **Dashboard**. Global info, settings, docs links and testing tiny service.
* **Web Services**. Manage all services and its instances: _tokens, users, functions_.

### Installation
---

1. Download the plugin zip-file
2. Add to your Moodle as administrator
    1. `Site administration` (**/admin/search.php**)
    2. `Plugins` tab
    3. `Install plugins` link (**/admin/tool/installaddon/index.php**).
    4. Upload `ZIP package` and click `Install plugin from ZIP file`

### Overview
---

Web Services Manager allows you to:

* Quickly check status/errors and enable or disable WS globally
* Add a new instance
* Switch REST and SOAP protocols
* Manage Mobile App settings
* Test Web Services through creating a tiny one just to look how does WS works
* Create a new instance
* Manage all of Web Services settings and instances in one window:
    * **Settings**
        * Check the info: `Short name`, `Time added`
        * Enable/disable WS
        * Change name (if it is custom WS)
        * `Authorised users only` switch
        * `Can download files` switch
        * `Can upload files` switch
    * **Manage Tokens**
        * View all WS tokens and its options:
            * Value
            * User
            * IP restriction
            * Valid until
            * Creator
        * Create WS tokens
        * Delete WS tokens
    * **Users** (_if `Authorised users only` enabled_)
        * View all WS users and options:
            * Full name
            * IP restriction
            * Valid until
        * Add WS users
        * Delete WS users
    * **Functions**
        * View all WS functions and options:
            * Function name
            * Function description
            * Test/execute function
        * Add WS function
        * Delete WS function

### Usage
---

As an administrator follow to:

1. `Site administration` (**/admin/search.php**)
2. `Server` tab (**/admin/search.php#linkserver**)
3. Find `Web services manager` section. There are two links: **Dashboard** and **Web services**

## Dashboard

Here you can control common settings of WS status.

![Moodle WSManager Dashboard Overview](https://github.com/ai/size-limit/assets/96910700/f5343e34-de33-4649-98b2-d023251d1bd0)

#### Status notification

If some options are breaking WS work, notification will show an error message with AJAX link to fix it to minimum
default settings to enable Web-Services. If notification message is `Web Services are ready to go` means everything
fine.

Error example. Just click on `Fix it` and confirm this action.

![Fix errors](https://github.com/ai/size-limit/assets/96910700/65557beb-0c4c-46ec-babd-a90b250ade13)

#### Mobile App

Moodle Mobile App works with enabled WS and has an own setting to enable/disable. You can switch it here or go to
native `Settings` section.

![Moodle Mobile App settings](https://github.com/ai/size-limit/assets/96910700/1fa8c762-f4c0-4641-948a-5718a6a97945)

#### Testing

This section is to make a simple test with tiny service and function handler. `Web service test client` link is an
internal Moodle plugin to test some simple functions.

![Moodle Web-Services Test Client](https://github.com/ai/size-limit/assets/96910700/54279ea1-4db3-4ffb-9bca-cdcc6a94ef22)

`Test function` widget allows to test built-in tiny simple function with input `data` argument that is returned.

1. Click on `Create Test Web Service` button and confirm modal dialog.
   ![Dialog confirm modal](https://github.com/ai/size-limit/assets/96910700/ed392d81-ff5b-4b22-bb76-08420e06ed3d)
2. New service with test function `local_wsmanager_external_function_test_handle` is created
   ![Created function](https://github.com/ai/size-limit/assets/96910700/dc9bb82a-2e24-476a-a8db-fa6fea3887f6)
3. Fields:
    1. `Enabled` - Web-Service enable/disable switcher. _For security reason, disable it if unusual_
    2. `Web Service` name
    3. `Function` name
    4. `Description`
    5. `Delete` button, here you can delete this WS
    6. `Type` read/write
    7. `Testing` button that opens a modal dialog to manage function execution

#### Documentation

List of useful links for Moodle Web-Services: documentation, Built-in external functions, Forum etc.

![Documentation links](https://github.com/ai/size-limit/assets/96910700/e9291ced-39e4-4f7b-b275-230bf9cd832a)

## Web Services

List of exists services with all manage functions

![Manage WS](https://github.com/ai/size-limit/assets/96910700/e9778195-96d6-45e9-b7b1-30966111bf3f)

#### Add service
---

Click on `+ Add service` link that shows the create form, fill it and save to add the one.

![Add Moodle Web Service](https://github.com/ai/size-limit/assets/96910700/33604a77-76aa-45b7-a7fa-d2d8eccb0915)

#### Services list sidebar

Services divided to `Built-in` (Mobile WS and External Plugins WS) and `Custom` (created by administrator). Here you can
navigate to a needle service.

#### Manage tokens

Token is a necessary parameter to make a request, this is a hashed unique string. Here administrator can add a new token
or delete an exist one.

![Tokens](https://github.com/ai/size-limit/assets/96910700/db3ab39e-2741-4697-86a4-eeba5511065d)

#### Users

If the service restricted with `Authorised users only` option, administrator can add users to WS.

![Users](https://github.com/ai/size-limit/assets/96910700/a436a1a5-f56c-4a04-b61b-2c39c654119a)

#### Functions

External function is a handler and main point of request.

![Functions](https://github.com/ai/size-limit/assets/96910700/0c11ed87-7a62-4c16-8f2e-880cf64146e7)

## Test/execute functions

Every request has necessary parameters, at least it's a token and function. Every function has own parameters and type.
In the plugin every external function has a manage button `Testing` (if WS Tokens are not empty) that shows a dialog
modal, here we can see all information about this function and also make a test/execution request. **But be sure before
you execute it that request is fine for you and security**. If the type of function is `read` - recommended request
method is `GET`, if `write` - `POST`.

![Function execute](https://github.com/ai/size-limit/assets/96910700/d42a6eb5-cd26-4286-9704-4c3387c223dd)

Let's take a look at function options

1. `Web Service` name of function WS
2. `Function` name
3. `Description`
4. `Type`: `read`/`write`
5. Request `Method`: `GET`/`POST`
6. `Protocol`: list of enabled protocols (at Dashboard)
7. `Format`: `JSON`/`XML` - only for `REST` protocol
8. Service `Token` to use in test request
9. Function parameters with its info. Fill an own data and data in `Request` widget will be updated on a blur event
   ![Param Info](https://github.com/ai/size-limit/assets/96910700/50ee4f8c-fce6-4eab-99f7-47db04aa5374)
10. `Request` widget. Here you can see all request info data
11. `Execute` button to send a test request. `Response` will be shown above.
    ![Response](https://github.com/ai/size-limit/assets/96910700/f7f4e476-cd36-4734-bafa-214c36ae3f4b)

## Credits

- [B. Desai](https://stackoverflow.com/users/7450125/b-desai): method `add_keys_dynamic`
  from [Stackoverflow](https://stackoverflow.com/a/44949080)

## License

The Moodle WSManager Moodle plugin is licensed under the terms of
the [GPL-3.0+ license](https://www.gnu.org/licenses/gpl-3.0.html) and is available for free.