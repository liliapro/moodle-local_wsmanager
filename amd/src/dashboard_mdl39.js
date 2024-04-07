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
 * Dashboard actions for Moodle >=3.9
 *
 * @module     local_wsmanager/dashboard_mdl39
 * @copyright  2024 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import {call as fetchMany} from 'core/ajax';
import ModalFactory from 'core/modal_factory';
import ModalEvents from 'core/modal_events';
import jQuery from 'jquery';

const local_wsmanager_protocols = () => {
    return jQuery('.local_wsmanager_protocol_check_row input[type=checkbox]').map(function () {
        return jQuery(this).data('protocol');
    }).get();
};

const local_wsmanager_webservice_dashboard_mobile_info_output = () => {
    fetchMany([{
        methodname: 'local_wsmanager_webservice_dashboard_mobile_info_output',
        args: {},
    }])[0].then(function (output) {
        jQuery('#local_wsmanager_webservice_dashboard_mobile_info_output').html(output);
    });
};

const local_wsmanager_webservice_dashboard_protocol_status_output = () => {
    var protocols = local_wsmanager_protocols();
    if (protocols.length > 0) {
        jQuery.each(protocols, function (i, protocol) {
            fetchMany([{
                methodname: 'local_wsmanager_webservice_protocol_status_output',
                args: {
                    protocol: protocol
                },
            }])[0].then(function (output) {
                jQuery('#local_wsmanager_protocol_status_' + protocol).html(output);
            });
        });
    }
};

const local_wsmanager_webservice_test_dashboard_table_output = (sep1, sep2, sep3, sep4) => {
    fetchMany([{
        methodname: 'local_wsmanager_webservice_test_dashboard_table_output',
        args: {},
    }])[0].then(function (output) {
        jQuery('#local_wsmanager_webservice_test_dashboard_table_output').html(output);
    });
};

const local_wsmanager_webservice_dashboard_info_output = () => {
    fetchMany([{
        methodname: 'local_wsmanager_webservice_dashboard_info_output',
        args: {},
    }])[0].then(function (output) {
        jQuery('#local_wsmanager_dashboard_info').html(output);
    });
};

const local_wsmanager_webservice_enable_handle = () => {
    jQuery('.local_wsmanager_enablewebservices_check').hide();
    jQuery('#local_wsmanager_enablewebservices_check_ok').show();
    jQuery('.local_wsmanager_protocol_check_row input[type=checkbox]')
        .prop('disabled', false);
    jQuery('#local_wsmanager_webservice_mobile_switch')
        .prop('disabled', false);
    local_wsmanager_webservice_dashboard_info_output();
    local_wsmanager_webservice_dashboard_protocol_status_output();
};

export const init = (sep1, sep2, sep3, sep4) => {
    jQuery('#local_wsmanager_switch_webservice_state').on('change', function () {
        var $this = jQuery(this);
        if (!$this.is(':checked')) {
            ModalFactory.create({
                type: ModalFactory.types.SAVE_CANCEL,
                title: M.str.local_wsmanager.webservices_disable,
                body: M.str.moodle.areyousure,
            }).then((modal) => {
                let root = modal.getRoot();
                modal.setButtonText('save', M.str.moodle.disable);
                root.on(ModalEvents.save, function (e) {
                    e.preventDefault();
                    fetchMany([{
                        methodname: 'local_wsmanager_webservices_state_switch',
                        args: {},
                    }])[0]
                        .then(function (res) {
                            if (res.enabled) {
                                jQuery('.local_wsmanager_enablewebservices_check').hide();
                                jQuery('#local_wsmanager_enablewebservices_check_error').show();
                                jQuery('.local_wsmanager_protocol_check_row input[type=checkbox]')
                                    .prop('checked', false).prop('disabled', true);
                                jQuery('#local_wsmanager_webservice_mobile_switch')
                                    .prop('checked', false).prop('disabled', true);
                                jQuery('.local_wsmanager_state_wrapper').hide();
                                local_wsmanager_webservice_dashboard_info_output();
                                local_wsmanager_webservice_dashboard_protocol_status_output();
                                local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
                                $this.prop('checked', false);
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
            fetchMany([{
                methodname: 'local_wsmanager_webservices_state_switch',
                args: {},
            }])[0].then(function (res) {
                if (!res.enabled) {
                    local_wsmanager_webservice_enable_handle();
                    local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
                    $this.prop('checked', true);
                    if (res.active) {
                        jQuery('.local_wsmanager_state_wrapper').show();
                    }
                }
            });
        }
        return false;
    });
    jQuery('.local_wsmanager_protocols_switch input[type=checkbox]').on('change', function () {
        var $this = jQuery(this),
            protocol = $this.data('protocol');
        if (!$this.is(':checked')) {
            ModalFactory.create({
                type: ModalFactory.types.SAVE_CANCEL,
                title: M.str.local_wsmanager.webservices_protocol_disable,
                body: M.str.moodle.areyousure,
            }).then((modal) => {
                let root = modal.getRoot();
                modal.setButtonText('save', M.str.moodle.disable);
                root.on(ModalEvents.save, function (e) {
                    e.preventDefault();
                    fetchMany([{
                        methodname: 'local_wsmanager_webservices_protocol_switch',
                        args: {
                            protocol: protocol
                        },
                    }])[0].then(function (res) {
                        if (res.enabled) {
                            local_wsmanager_webservice_dashboard_info_output();
                            local_wsmanager_webservice_dashboard_protocol_status_output();
                            local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
                            $this.prop('checked', false);
                        }
                        if (res.active) {
                            jQuery('.local_wsmanager_state_wrapper').show();
                        } else {
                            jQuery('.local_wsmanager_state_wrapper').hide();
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
            fetchMany([{
                methodname: 'local_wsmanager_webservices_protocol_switch',
                args: {
                    protocol: protocol,
                },
            }])[0].then(function (res) {
                if (!res.enabled) {
                    local_wsmanager_webservice_dashboard_info_output();
                    local_wsmanager_webservice_dashboard_protocol_status_output();
                    local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
                    $this.prop('checked', true);
                }
                if (res.active) {
                    jQuery('.local_wsmanager_state_wrapper').show();
                } else {
                    jQuery('.local_wsmanager_state_wrapper').hide();
                }
            });
        }
        return false;
    });
    jQuery('#local_wsmanager_switch_wsdocumentation').on('change', function () {
        var $this = jQuery(this);
        fetchMany([{
            methodname: 'local_wsmanager_webservice_documentation_switch',
            args: {},
        }])[0].then(function (switched) {
            //nothing to do
        });
        return false;
    });
    jQuery('body')
        .on('change', '#local_wsmanager_webservice_mobile_switch', function (event) {
            var $this = jQuery(this);
            if (!$this.is(':checked')) {
                ModalFactory.create({
                    type: ModalFactory.types.SAVE_CANCEL,
                    title: M.str.local_wsmanager.webservices_protocol_disable,
                    body: M.str.moodle.areyousure,
                }).then((modal) => {
                    let root = modal.getRoot();
                    modal.setButtonText('save', M.str.moodle.disable);
                    root.on(ModalEvents.save, function (e) {
                        e.preventDefault();
                        fetchMany([{
                            methodname: 'local_wsmanager_webservice_mobile_state_switch',
                            args: {},
                        }])[0].then(function (res) {
                            if (res.enabled) {
                                jQuery('.local_wsmanager_mobileapp_check').hide();
                                jQuery('#local_wsmanager_mobileapp_check_error').show();
                                local_wsmanager_webservice_dashboard_mobile_info_output();
                                $this.prop('checked', false);
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
                fetchMany([{
                    methodname: 'local_wsmanager_webservice_mobile_state_switch',
                    args: {},
                }])[0].then(function (res) {
                    if (!res.enabled) {
                        jQuery('.local_wsmanager_mobileapp_check').hide();
                        jQuery('#local_wsmanager_mobileapp_check_ok').show();
                        local_wsmanager_webservice_dashboard_mobile_info_output();
                        $this.prop('checked', true);
                    }
                });
            }
            return false;
        })
        .on('click', '#local_wsmanager_webservices_fix', function (event) {
            event.preventDefault();
            var $this = jQuery(this);
            fetchMany([{
                methodname: 'local_wsmanager_webservices_enable_fix',
                args: {},
            }])[0].then(function (res) {
                if (res.enablewebservices) {
                    local_wsmanager_webservice_enable_handle();
                    jQuery('#local_wsmanager_switch_webservice_state').prop('checked', true);
                }
                if (res.webserviceprotocols) {
                    local_wsmanager_webservice_enable_handle();
                    local_wsmanager_webservice_dashboard_protocol_status_output();
                    jQuery('#local_wsmanager_protocol_switch_rest').prop('checked', true);
                }
            });
            return false;
        })
        .on('click', '#local_wsmanager_webservice_test_create_button', function (event) {
            event.preventDefault();
            var $this = jQuery(this);
            ModalFactory.create({
                type: ModalFactory.types.SAVE_CANCEL,
                title: M.str.local_wsmanager.webservice_test_create,
                body: M.str.moodle.areyousure,
            }).then((modal) => {
                let root = modal.getRoot();
                modal.setButtonText('save', M.str.local_wsmanager.webservice_test_create);
                root.on(ModalEvents.save, function (e) {
                    e.preventDefault();
                    fetchMany([{
                        methodname: 'local_wsmanager_webservice_test_create',
                        args: {},
                    }])[0]
                        .then(function (res) {
                            if (res) {
                                local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
                            }
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
        .on('click', '#local_wsmanager_webservice_test_delete', function (event) {
            event.preventDefault();
            var $this = jQuery(this),
                webserviceid = parseInt($this.data('webserviceid'));
            if (webserviceid) {
                ModalFactory.create({
                    type: ModalFactory.types.SAVE_CANCEL,
                    title: M.str.local_wsmanager.webservice_test_delete,
                    body: M.str.moodle.areyousure,
                }).then((modal) => {
                    let root = modal.getRoot();
                    modal.setButtonText('save', M.str.moodle.delete);
                    root.on(ModalEvents.save, function (e) {
                        e.preventDefault();
                        fetchMany([{
                            methodname: 'local_wsmanager_webservice_delete',
                            args: {
                                webserviceid: webserviceid,
                            },
                        }])[0].then(function (res) {
                            local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
                        });
                        modal.hide();
                    });
                    root.on(ModalEvents.cancel, function () {
                        modal.hide();
                    });
                    modal.show();
                });
            }

            return false;
        });
};