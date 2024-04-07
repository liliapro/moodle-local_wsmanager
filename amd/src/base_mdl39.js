// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Base actions for Moodle >=3.9
 *
 * @module     local_wsmanager/base_mdl39
 * @copyright  2024 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {call as fetchMany} from 'core/ajax';
import ModalFactory from 'core/modal_factory';
import ModalEvents from 'core/modal_events';
import jQuery from 'jquery';

const local_wsmanager_webservice_nav_title = (webserviceid) => {
    return fetchMany([{
        methodname: 'local_wsmanager_webservice_nav_title',
        args: {
            webserviceid: webserviceid,
        },
    }])[0]
        .then(function (response) {
            jQuery('#local_wsmanager_tab_link_webservice_' + webserviceid).html(response);
        });
};

const local_wsmanager_users_table_output = (webserviceid) => {
    return fetchMany([{
        methodname: 'local_wsmanager_webservice_users_table_output',
        args: {
            webserviceid: webserviceid,
        },
    }])[0].then(function (res) {
        jQuery('#local_wsmanager_webservice_users_table_' + webserviceid).html(res);
    });
};

const local_wsmanager_webservice_functions_get_params = (webserviceid, functionname, sep1, sep2, sep3, sep4) => {
    var ret = '';
    jQuery('[id*="local_wsmanager_ws_fn_param_' + webserviceid + '_' + functionname + '"]').each(function () {
        var $this = jQuery(this),
            arr = $this.attr('id').replace('local_wsmanager_ws_fn_param_' + webserviceid + '_' + functionname, '').split('_');
        ret += webserviceid + sep1 + functionname + sep1 + arr[1] + sep1 + $this.val() + sep4;
    });
    return ret;
};

const local_wsmanager_request_info = (webserviceid, functionname, params, method, protocol, restformat, token) => {
    if (!method) {
        method = jQuery('#local_wsmanager_external_function_handle_method_' + webserviceid + '_' +
            functionname + ' select').val();
    }
    if (!protocol) {
        protocol = jQuery('#local_wsmanager_external_function_handle_protocol_' + webserviceid + '_' +
            functionname + ' select').val();
    }
    if (!restformat) {
        restformat = jQuery('#local_wsmanager_external_function_handle_restformat_' + webserviceid + '_' +
            functionname + ' select').val();
    }
    if (!token) {
        token = jQuery('#local_wsmanager_external_function_handle_token_' + webserviceid + '_' + functionname +
            ' select option:selected').text();
    }
    return fetchMany([{
        methodname: 'local_wsmanager_request_info',
        args: {
            webserviceid: webserviceid,
            functionname: functionname,
            params: params,
            method: method,
            protocol: protocol,
            restformat: restformat,
            token: token,
        },
    }])[0].then(function (res) {
        jQuery('#local_wsmanager_request_info_' + webserviceid + '_' + functionname).html(res);
    });
};

const local_wsmanager_external_function_handle_output = (functionname, webserviceid) => {
    return fetchMany([{
        methodname: 'local_wsmanager_external_function_handle_output',
        args: {
            functionname: functionname,
            webserviceid: webserviceid,
        },
    }])[0].then(function (res) {
        return res;
    });
};

export const init = async (sep1, sep2, sep3, sep4) => {
    jQuery('body')
        .on('change', '[data-switch="webservice"]', function (event) {
            var $this = jQuery(this),
                webserviceid = parseInt($this.data('webserviceid')),
                instance = $this.data('instance'),
                name = $this.data('name');
            //disable
            if (!$this.is(':checked')) {
                ModalFactory.create({
                    type: ModalFactory.types.SAVE_CANCEL,
                    title: M.str.moodle.disable,
                    body: M.str.moodle.disable + '<strong>' + name + '</strong><br>' + M.str.moodle.areyousure,
                }).then((modal) => {
                    let root = modal.getRoot();
                    modal.setButtonText('save', M.str.moodle.disable);
                    root.on(ModalEvents.save, function (e) {
                        e.preventDefault();
                        fetchMany([{
                            methodname: 'local_wsmanager_webservice_state_switch',
                            args: {
                                webserviceid: webserviceid,
                                instance: instance
                            },
                        }])[0].then(function (response) {
                            switch (instance) {
                                case 'restrictedusers':
                                    local_wsmanager_users_table_output(webserviceid);
                                    break;
                                case 'enabled':
                                    local_wsmanager_webservice_nav_title(webserviceid);
                                    jQuery('#local_wsmanager_webservice_row_' + webserviceid)
                                        .addClass('local_wsmanager_webservice_row_danger');
                                    jQuery('#local_wsmanager_webservice_name_' + webserviceid).attr('disabled', true);
                                    break;
                            }
                        });
                        modal.hide();
                    });
                    root.on(ModalEvents.cancel, function () {
                        $this.prop('checked', true);
                        modal.hide();
                    });
                    modal.show();
                });
            } else {
                //enable
                fetchMany([{
                    methodname: 'local_wsmanager_webservice_state_switch',
                    args: {
                        webserviceid: webserviceid,
                        instance: instance
                    },
                }])[0].then(function (response) {
                    switch (instance) {
                        case 'restrictedusers':
                            local_wsmanager_users_table_output(webserviceid);
                            break;
                        case 'enabled':
                            local_wsmanager_webservice_nav_title(webserviceid);
                            jQuery('#local_wsmanager_webservice_row_' + webserviceid)
                                .removeClass('local_wsmanager_webservice_row_danger');
                            jQuery('#local_wsmanager_webservice_name_' + webserviceid).attr('disabled', false);
                            break;
                    }
                });
            }
            return false;
        })
        .on('click', '.local_wsmanager_external_function_handle_wrapper', function (event) {
            var $this = jQuery(this),
                webserviceid = parseInt($this.data('webserviceid')),
                functionname = $this.data('functionname');
            event.preventDefault();
            ModalFactory.create({
                type: ModalFactory.types.DEFAULT,
                large: true,
                title: M.str.local_wsmanager.test,
                body: local_wsmanager_external_function_handle_output(
                    functionname,
                    webserviceid
                ),
            }).then(function (modal) {
                modal.show();
            });
            return false;
        })
        .on('change', '.local_wsmanager_external_function_handle_select select', function (event) {
            event.preventDefault();
            var $this = jQuery(this),
                parent = $this.closest('.local_wsmanager_external_function_handle_select'),
                webserviceid = parseInt(parent.data('webserviceid')),
                functionname = parent.data('functionname'),
                params = local_wsmanager_webservice_functions_get_params(webserviceid, functionname, sep1, sep2, sep3, sep4),
                method = jQuery('#local_wsmanager_external_function_handle_method_' + webserviceid + '_' + functionname + ' select').val(),
                protocol = jQuery('#local_wsmanager_external_function_handle_protocol_' + webserviceid + '_' + functionname + ' select').val(),
                restformat = jQuery('#local_wsmanager_external_function_handle_restformat_' + webserviceid + '_' + functionname + ' select').val(),
                token = jQuery('#local_wsmanager_external_function_handle_token_' + webserviceid + '_' + functionname + ' select option:selected').text(),
                instance = parent.data('instance');
            switch (instance) {
                case 'protocol':
                    if ($this.val() != 'rest') {
                        jQuery('#local_wsmanager_external_function_handle_restformat_row_' + webserviceid + '_' + functionname).hide();
                    } else {
                        jQuery('#local_wsmanager_external_function_handle_restformat_row_' + webserviceid + '_' + functionname).show();
                    }
                    break;
            }
            return local_wsmanager_request_info(webserviceid, functionname, params, method, protocol, restformat, token);
        })
        .on('blur', '.local_wsmanager_webservice_function_param_value input,' +
            '.local_wsmanager_webservice_function_param_value textarea', function (event) {
            event.preventDefault();
            var $this = jQuery(this),
                parent = $this.closest('.local_wsmanager_webservice_function_param_value'),
                webserviceid = parseInt(parent.data('webserviceid')),
                functionname = parent.data('functionname'),
                params = local_wsmanager_webservice_functions_get_params(webserviceid, functionname, sep1, sep2, sep3, sep4),
                method = jQuery('#local_wsmanager_external_function_handle_method_' + webserviceid + '_' + functionname + ' select').val(),
                protocol = jQuery('#local_wsmanager_external_function_handle_protocol_' + webserviceid + '_' + functionname + ' select').val(),
                restformat = jQuery('#local_wsmanager_external_function_handle_restformat_' + webserviceid + '_' + functionname + ' select').val(),
                token = jQuery('#local_wsmanager_external_function_handle_token_' + webserviceid + '_' + functionname + ' select option:selected').text();
            return local_wsmanager_request_info(webserviceid, functionname, params, method, protocol, restformat, token);
        })
        .on('click', '.local_wsmanager_external_function_handle', function (event) {
            event.preventDefault();
            var $this = jQuery(this),
                webserviceid = parseInt($this.data('webserviceid')),
                functionname = $this.data('functionname'),
                token = jQuery('#local_wsmanager_external_function_handle_token_' + webserviceid + '_' +
                    functionname + ' select option:selected').text(),
                params = local_wsmanager_webservice_functions_get_params(webserviceid, functionname, sep1, sep2, sep3, sep4),
                method = jQuery('#local_wsmanager_external_function_handle_method_' + webserviceid + '_' +
                    functionname + ' select').val(),
                protocol = jQuery('#local_wsmanager_external_function_handle_protocol_' + webserviceid + '_' +
                    functionname + ' select').val(),
                restformat = jQuery('#local_wsmanager_external_function_handle_restformat_' + webserviceid + '_' +
                    functionname + ' select').val();
            ModalFactory.create({
                type: ModalFactory.types.SAVE_CANCEL,
                title: M.str.webservice.execute,
                body: M.str.webservice.execute + ' <strong>' + functionname + '</strong><br />' +
                    M.str.moodle.areyousure,
            }).then(function (modal) {
                let root = modal.getRoot();
                modal.setButtonText('save', M.str.moodle.yes);
                root.on(ModalEvents.save, function (e) {
                    e.preventDefault();
                    fetchMany([{
                        methodname: 'local_wsmanager_external_function_handle',
                        args: {
                            webserviceid: webserviceid,
                            functionname: functionname,
                            token: token,
                            params: params,
                            method: method,
                            moodlewsprotocol: protocol,
                            moodlewsrestformat: restformat
                        },
                    }])[0].then(function (res) {
                        jQuery('#local_wsmanager_external_function_handle_response_content_' + webserviceid + '_' + functionname).html(res);
                        jQuery('#local_wsmanager_external_function_handle_response_' + webserviceid + '_' + functionname).show();
                    });
                    modal.hide();
                });
                root.on(ModalEvents.cancel, function () {
                    modal.hide();
                });
                modal.show();
            });
            return false;
        })
        .on('click', '.local_wsmanager_webservice_name_update', function (event) {
            event.preventDefault();
            var $this = jQuery(this),
                webserviceid = parseInt($this.data('webserviceid')),
                name = jQuery('#local_wsmanager_webservice_name_' + webserviceid).val();
            fetchMany([{
                methodname: 'local_wsmanager_webservice_rename',
                args: {
                    webserviceid: webserviceid,
                    name: name
                },
            }])[0].then(function (res) {
                ModalFactory.create({
                    type: ModalFactory.types.DEFAULT,
                    title: M.str.moodle.summary,
                    body: res ? M.str.moodle.success : M.str.moodle.error,
                }).then(function (modal) {
                    let root = modal.getRoot();
                    root.on(ModalEvents.cancel, function () {
                        modal.hide();
                    });
                    modal.show();
                    if (res) {
                        jQuery('#local_wsmanager_tab_link_webservice_' + webserviceid).html(name);
                    }
                });
            });
            return false;
        });
};