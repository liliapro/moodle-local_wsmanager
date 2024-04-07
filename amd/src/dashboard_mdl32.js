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
 * Dashboard actions for Moodle >=3.2
 *
 * @module     local_wsmanager/dashboard_mdl32
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([
    'jquery',
    'core/ajax',
    'core/modal_factory',
    'core/modal_events',
    'local_wsmanager/base_mdl32'
], function (
    jQuery,
    ajax,
    ModalFactory,
    ModalEvents,
    LocalWSManagerBase
) {
    return {
        local_wsmanager_protocols: function () {
            return jQuery('.local_wsmanager_protocol_check_row input[type=checkbox]').map(function () {
                return jQuery(this).data('protocol');
            }).get();
        },
        local_wsmanager_webservice_dashboard_mobile_info_output: function () {
            return ajax.call([{
                methodname: 'local_wsmanager_webservice_dashboard_mobile_info_output',
                args: {}
            }])[0]
                .then(function (output) {
                    jQuery('#local_wsmanager_webservice_dashboard_mobile_info_output').html(output);
                });
        },
        local_wsmanager_webservice_dashboard_protocol_status_output: function () {
            var protocols = this.local_wsmanager_protocols();
            if (protocols.length > 0) {
                jQuery.each(protocols, function (i, protocol) {
                    ajax.call([{
                        methodname: 'local_wsmanager_webservice_protocol_status_output',
                        args: {
                            protocol: protocol
                        }
                    }])[0]
                        .then(function (output) {
                            jQuery('#local_wsmanager_protocol_status_' + protocol).html(output);
                        });
                });
            }
        },
        local_wsmanager_webservice_test_dashboard_table_output: function (sep1, sep2, sep3, sep4) {
            return ajax.call([{
                methodname: 'local_wsmanager_webservice_test_dashboard_table_output',
                args: {}
            }])[0]
                .then(function (output) {
                    jQuery('#local_wsmanager_webservice_test_dashboard_table_output').html(output);
                });
        },
        local_wsmanager_webservice_dashboard_info_output: function () {
            return ajax.call([{
                methodname: 'local_wsmanager_webservice_dashboard_info_output',
                args: {}
            }])[0]
                .then(function (output) {
                    jQuery('#local_wsmanager_dashboard_info').html(output);
                });
        },
        local_wsmanager_webservice_enable_handle: function () {
            jQuery('.local_wsmanager_enablewebservices_check').hide();
            jQuery('#local_wsmanager_enablewebservices_check_ok').show();
            jQuery('.local_wsmanager_protocol_check_row input[type=checkbox]')
                .prop('disabled', false);
            jQuery('#local_wsmanager_webservice_mobile_switch')
                .prop('disabled', false);
            this.local_wsmanager_webservice_dashboard_info_output();
            this.local_wsmanager_webservice_dashboard_protocol_status_output();
        },
        init: function (sep1, sep2, sep3, sep4) {
            var ctx = this;
            jQuery('#local_wsmanager_switch_webservice_state').on('change', function () {
                var $this = jQuery(this);
                if (!$this.is(':checked')) {
                    ModalFactory.create({
                        type: ModalFactory.types.SAVE_CANCEL,
                        title: M.str.local_wsmanager.webservices_disable,
                        body: M.str.moodle.areyousure
                    })
                        .then(function (modal) {
                            modal.setButtonText('save', M.str.moodle.disable);
                            modal.getRoot().on(ModalEvents.save, function (e) {
                                e.preventDefault();
                                ajax.call([{
                                    methodname: 'local_wsmanager_webservices_state_switch',
                                    args: {}
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
                                            ctx.local_wsmanager_webservice_dashboard_info_output();
                                            ctx.local_wsmanager_webservice_dashboard_protocol_status_output();
                                            ctx.local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
                                            $this.prop('checked', false);
                                        }
                                    });
                                modal.hide();
                            });
                            modal.getRoot().on(ModalEvents.cancel, function () {
                                $this.prop('checked', true);
                                modal.hide();
                            });
                            modal.show();
                        });
                } else {
                    ajax.call([{
                        methodname: 'local_wsmanager_webservices_state_switch',
                        args: {}
                    }])[0]
                        .then(function (res) {
                            if (!res.enabled) {
                                ctx.local_wsmanager_webservice_enable_handle();
                                ctx.local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
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
                        body: M.str.moodle.areyousure
                    })
                        .then(function (modal) {
                            modal.setButtonText('save', M.str.moodle.disable);
                            modal.getRoot().on(ModalEvents.save, function (e) {
                                e.preventDefault();
                                ajax.call([{
                                    methodname: 'local_wsmanager_webservices_protocol_switch',
                                    args: {
                                        protocol: protocol
                                    }
                                }])[0]
                                    .then(function (res) {
                                        if (res.enabled) {
                                            ctx.local_wsmanager_webservice_dashboard_info_output();
                                            ctx.local_wsmanager_webservice_dashboard_protocol_status_output();
                                            ctx.local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
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
                            modal.getRoot().on(ModalEvents.cancel, function () {
                                $this.prop('checked', true);
                                modal.hide();
                            });
                            modal.show();
                        });
                } else {
                    ajax.call([{
                        methodname: 'local_wsmanager_webservices_protocol_switch',
                        args: {
                            protocol: protocol
                        }
                    }])[0]
                        .then(function (res) {
                            if (!res.enabled) {
                                ctx.local_wsmanager_webservice_dashboard_info_output();
                                ctx.local_wsmanager_webservice_dashboard_protocol_status_output();
                                ctx.local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
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
                ajax.call([{
                    methodname: 'local_wsmanager_webservice_documentation_switch',
                    args: {}
                }])[0]
                    .then(function (switched) {
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
                            body: M.str.moodle.areyousure
                        })
                            .then(function (modal) {
                                modal.setButtonText('save', M.str.moodle.disable);
                                modal.getRoot().on(ModalEvents.save, function (e) {
                                    e.preventDefault();
                                    ajax.call([{
                                        methodname: 'local_wsmanager_webservice_mobile_state_switch',
                                        args: {}
                                    }])[0]
                                        .then(function (res) {
                                            if (res.enabled) {
                                                jQuery('.local_wsmanager_mobileapp_check').hide();
                                                jQuery('#local_wsmanager_mobileapp_check_error').show();
                                                ctx.local_wsmanager_webservice_dashboard_mobile_info_output();
                                                $this.prop('checked', false);
                                            }
                                        });
                                    modal.hide();
                                });
                                modal.getRoot().on(ModalEvents.cancel, function () {
                                    $this.prop('checked', true);
                                    modal.hide();
                                });
                                modal.show();
                            });
                    } else {
                        ajax.call([{
                            methodname: 'local_wsmanager_webservice_mobile_state_switch',
                            args: {}
                        }])[0]
                            .then(function (res) {
                                if (!res.enabled) {
                                    jQuery('.local_wsmanager_mobileapp_check').hide();
                                    jQuery('#local_wsmanager_mobileapp_check_ok').show();
                                    ctx.local_wsmanager_webservice_dashboard_mobile_info_output();
                                    $this.prop('checked', true);
                                }
                            });
                    }
                    return false;
                })
                .on('click', '#local_wsmanager_webservices_fix', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this);
                    ajax.call([{
                        methodname: 'local_wsmanager_webservices_enable_fix',
                        args: {}
                    }])[0]
                        .then(function (res) {
                            if (res.enablewebservices) {
                                ctx.local_wsmanager_webservice_enable_handle();
                                jQuery('#local_wsmanager_switch_webservice_state').prop('checked', true);
                            }
                            if (res.webserviceprotocols) {
                                ctx.local_wsmanager_webservice_enable_handle();
                                ctx.local_wsmanager_webservice_dashboard_protocol_status_output();
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
                        body: M.str.moodle.areyousure
                    }).then(function (modal) {
                        modal.setButtonText('save', M.str.local_wsmanager.webservice_test_create);
                        modal.getRoot().on(ModalEvents.save, function (e) {
                            e.preventDefault();
                            ajax.call([{
                                methodname: 'local_wsmanager_webservice_test_create',
                                args: {}
                            }])[0]
                                .then(function (res) {
                                    if (res) {
                                        ctx.local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
                                    }
                                });
                            modal.hide();
                        });
                        modal.getRoot().on(ModalEvents.cancel, function () {
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
                            body: M.str.moodle.areyousure
                        }).then(function (modal) {
                            modal.setButtonText('save', M.str.moodle.delete);
                            modal.getRoot().on(ModalEvents.save, function (e) {
                                e.preventDefault();
                                ajax.call([{
                                    methodname: 'local_wsmanager_webservice_delete',
                                    args: {
                                        webserviceid: webserviceid
                                    }
                                }])[0]
                                    .then(function (res) {
                                        ctx.local_wsmanager_webservice_test_dashboard_table_output(sep1, sep2, sep3, sep4);
                                    });
                                modal.hide();
                            });
                            modal.getRoot().on(ModalEvents.cancel, function () {
                                modal.hide();
                            });
                            modal.show();
                        });
                    }

                    return false;
                });
        },
    };
});