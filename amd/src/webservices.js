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
 * Webservices actions.
 *
 * @module     local_wsmanager/webservices
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([
    'jquery',
    'core/ajax',
    'core/modal_factory',
    'core/modal_events',
    'local_wsmanager/base'
], function (
    jQuery,
    ajax,
    ModalFactory,
    ModalEvents,
    LocalWSManagerBase
) {
    return {
        local_wsmanager_webservice_tokens_table_output: function (webserviceid) {
            return ajax.call([{
                methodname: 'local_wsmanager_webservice_tokens_table_output',
                args: {
                    webserviceid: webserviceid
                }
            }])[0]
                .then(function (res) {
                    jQuery('#local_wsmanager_webservice_tokens_table_' + webserviceid).html(res);
                });
        },
        local_wsmanager_functions_table_output: function (webserviceid) {
            return ajax.call([{
                methodname: 'local_wsmanager_webservice_functions_table_output',
                args: {
                    webserviceid: webserviceid
                }
            }])[0]
                .then(function (res) {
                    jQuery('#local_wsmanager_webservice_functions_table_' + id).html(res);
                });
        },
        init: function (sep1, sep2, sep3, sep4) {
            var ctx = this,
                anchor = window.location.hash.substring(1);
            if (anchor) {
                switch (anchor) {
                    case 'create_webservice_form':
                        jQuery('#local_wsmanager_create_webservice_form').show();
                        jQuery('#local_wsmanager_create_webservice_form_show').addClass('opened');
                        break;
                }
            }
            jQuery('body')
                .on('click', '.local_wsmanager_tab_link', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        webserviceid = $this.data('webserviceid');
                    jQuery('.local_wsmanager_tab_link').removeClass('active');
                    $this.addClass('active');
                    jQuery('.local_wsmanager_webservice_data_output').hide();
                    jQuery('#local_wsmanager_webservice_data_output_' + webserviceid).show();
                    return false;
                })
                .on('click', '#local_wsmanager_create_webservice_form_show', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this);
                    if (!$this.hasClass('opened')) {
                        jQuery('#local_wsmanager_create_webservice_form').show();
                        $this.addClass('opened');
                    } else {
                        jQuery('#local_wsmanager_create_webservice_form').hide();
                        $this.removeClass('opened');
                    }
                    return false;
                })
                .on('submit', '#local_wsmanager_create_webservice_form form', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        data = {},
                        formData = $this.serializeArray();
                    if (formData) {
                        jQuery.each(formData, function (i, item) {
                            if (item.name) {
                                switch (item.name) {
                                    case 'sesskey':
                                        data.sesskey = item.value;
                                        break;
                                    case '_qf__local_wsmanager_webservice_form':
                                        data._qf__local_wsmanager_webservice_form = parseInt(item.value);
                                        break;
                                    case 'name':
                                        data.name = item.value;
                                        break;
                                    case 'shortname':
                                        data.shortname = item.value;
                                        break;
                                    case 'enabled':
                                        data.enabled = parseInt(item.value);
                                        break;
                                    case 'restrictedusers':
                                        data.restrictedusers = parseInt(item.value);
                                        break;
                                    case 'downloadfiles':
                                        data.downloadfiles = parseInt(item.value);
                                        break;
                                    case 'uploadfiles':
                                        data.uploadfiles = parseInt(item.value);
                                        break;
                                    case 'requiredcapability':
                                        data.requiredcapability = item.value;
                                        break;
                                }
                            }
                        });
                        jQuery.post(
                            M.cfg.wwwroot + '/local/wsmanager/webservices.php',
                            data,
                            function (response) {
                                if (response.success) {
                                    ajax.call([{
                                        methodname: 'local_wsmanager_webservice_create',
                                        args: {
                                            name: data.name,
                                            shortname: data.shortname,
                                            enabled: data.enabled,
                                            restrictedusers: data.restrictedusers,
                                            downloadfiles: data.downloadfiles,
                                            uploadfiles: data.uploadfiles,
                                            requiredcapability: data.requiredcapability
                                        }
                                    }])[0]
                                        .then(function (res) {
                                            if (res.errors.length === 0 && res.errors.webservice) {
                                                window.location.replace(jQuery('#local_wsmanager_url').val());
                                            }
                                        });
                                }
                            },
                            'json'
                        );
                    }
                    return false;
                })
                .on('click', '#local_wsmanager_create_webservice_form [name="cancel"]', function (event) {
                    event.preventDefault();
                    jQuery('#local_wsmanager_create_webservice_form').hide();
                    return false;
                })
                .on('click', '.local_wsmanager_webservice_token_create_form_button', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        webserviceid = $this.data('webserviceid');
                    jQuery('#local_wsmanager_webservice_token_create_form_wrapper_' + webserviceid).show();
                    $this.hide();
                    return false;
                })
                .on('submit', '.local_wsmanager_webservice_token_create_form', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        data = {
                            valid_until: {}
                        },
                        formData = $this.serializeArray();
                    if (formData) {
                        jQuery.each(formData, function (i, item) {
                            if (item.name) {
                                switch (item.name) {
                                    case 'webserviceid':
                                        data.webserviceid = parseInt(item.value);
                                        break;
                                    case 'sesskey':
                                        data.sesskey = item.value;
                                        break;
                                    case '_qf__local_wsmanager_token_form':
                                        data._qf__local_wsmanager_token_form = parseInt(item.value);
                                        break;
                                    case 'user':
                                        data.user = parseInt(item.value);
                                        break;
                                    case 'iprestriction':
                                        data.iprestriction = jQuery.trim(item.value);
                                        break;
                                    case 'validuntil[enabled]':
                                        data.validUntilEnabled = true;
                                        break;
                                    case 'validuntil[day]':
                                        data.valid_until.day = parseInt(item.value);
                                        break;
                                    case 'validuntil[month]':
                                        data.valid_until.month = parseInt(item.value);
                                        break;
                                    case 'validuntil[year]':
                                        data.valid_until.year = parseInt(item.value);
                                        break;
                                }
                            }
                        });
                        if (!data.validUntilEnabled) {
                            data.valid_until = {};
                        }
                        jQuery.post(
                            M.cfg.wwwroot + '/local/wsmanager/webservices.php',
                            data,
                            function (response) {
                                if (response.success) {
                                    ajax.call([{
                                        methodname: 'local_wsmanager_webservice_token_create',
                                        args: {
                                            webserviceid: data.webserviceid,
                                            userid: data.user,
                                            iprestriction: data.iprestriction,
                                            valid_until: data.valid_until
                                        }
                                    }])[0]
                                        .then(function (res) {
                                            if (res.id) {
                                                ctx.local_wsmanager_webservice_tokens_table_output(data.webserviceid);
                                            }
                                        });
                                }
                            },
                            'json'
                        );
                    }
                    return false;
                })
                .on('click', '.local_wsmanager_webservice_token_create_form_wrapper [name="cancel"]', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        parent = $this.closest('.local_wsmanager_webservice_token_create_form_wrapper'),
                        webserviceid = 0;
                    if (parent) {
                        webserviceid = parent.data('webserviceid');
                        jQuery('#local_wsmanager_webservice_token_create_form_button_' + webserviceid).show();
                        jQuery('#local_wsmanager_webservice_token_create_form_wrapper_' + webserviceid).hide();
                    }
                    return false;
                })
                .on('click', '.local_wsmanager_webservice_token_delete', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        webserviceid = parseInt($this.data('webserviceid')),
                        tokenid = parseInt($this.data('tokenid')),
                        token = $this.data('token');
                    if (tokenid) {
                        ModalFactory.create({
                            type: ModalFactory.types.SAVE_CANCEL,
                            title: M.str.webservice.deletetoken,
                            body: M.str.webservice.deletetoken + ' <strong>' + token + '</strong><br />' +
                                M.str.moodle.areyousure
                        })
                            .then(function (modal) {
                                modal.setButtonText('save', M.str.moodle.delete);
                                modal.getRoot().on(ModalEvents.save, function (e) {
                                    e.preventDefault();
                                    ajax.call([{
                                        methodname: 'local_wsmanager_webservice_token_delete',
                                        args: {
                                            tokenid: tokenid
                                        }
                                    }])[0]
                                        .then(function (response) {
                                            ctx.local_wsmanager_webservice_tokens_table_output(webserviceid);
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
                })
                .on('click', '.local_wsmanager_webservice_functions_add_form_button', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        webserviceid = $this.data('webserviceid');
                    jQuery('#local_wsmanager_webservice_functions_add_form_wrapper_' + webserviceid).show();
                    $this.hide();
                    return false;
                })
                .on('submit', '.local_wsmanager_webservice_functions_add_form', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        fids = [],
                        webserviceid = $this.data('webserviceid'),
                        formData = $this.serializeArray();
                    if (formData) {
                        jQuery.each(formData, function (i, item) {
                            if (item.name) {
                                switch (item.name) {
                                    case 'fids_' + webserviceid + '[]':
                                        fids.push(parseInt(item.value));
                                        break;
                                }
                            }
                        });
                        if (fids.length) {
                            jQuery.each(fids, function (i, fid) {
                                ajax.call([{
                                    methodname: 'local_wsmanager_webservice_function_add',
                                    args: {
                                        functionid: fid,
                                        webserviceid: webserviceid
                                    }
                                }])[0]
                                    .then(function (response) {
                                        ctx.local_wsmanager_functions_table_output(webserviceid);
                                    });
                            });
                        }
                    }
                    return false;
                })
                .on('click', '.local_wsmanager_webservice_functions_add_form_wrapper [name="cancel"]', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        parent = $this.closest('.local_wsmanager_webservice_functions_add_form_wrapper'),
                        webserviceid = 0;
                    if (parent) {
                        webserviceid = parent.data('webserviceid');
                        jQuery('#local_wsmanager_webservice_functions_add_form_button_' + webserviceid).show();
                        jQuery('#local_wsmanager_webservice_functions_add_form_wrapper_' + webserviceid).hide();
                    }
                    return false;
                })
                .on('click', '.local_wsmanager_webservice_function_delete', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        webserviceid = parseInt($this.data('webserviceid')),
                        functionid = parseInt($this.data('functionid')),
                        functionname = $this.data('functionname');
                    if (webserviceid && functionid && functionname) {
                        ModalFactory.create({
                            type: ModalFactory.types.SAVE_CANCEL,
                            title: M.str.webservice.removefunction,
                            body: M.str.webservice.removefunction + ' <strong>' + functionname + '</strong><br />' +
                                M.str.moodle.areyousure
                        })
                            .then(function (modal) {
                                modal.setButtonText('save', M.str.moodle.remove);
                                modal.getRoot().on(ModalEvents.save, function (e) {
                                    e.preventDefault();
                                    ajax.call([{
                                        methodname: 'local_wsmanager_webservice_function_delete',
                                        args: {
                                            functionid: functionid,
                                            webserviceid: webserviceid
                                        }
                                    }])[0]
                                        .then(function (response) {
                                            ctx.local_wsmanager_functions_table_output(webserviceid);
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
                })
                .on('click', '.local_wsmanager_webservice_delete', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        webserviceid = parseInt($this.data('webserviceid')),
                        name = $this.data('name');
                    if (webserviceid) {
                        ModalFactory.create({
                            type: ModalFactory.types.SAVE_CANCEL,
                            title: M.str.webservice.deleteaservice,
                            body: M.str.webservice.deleteaservice + ' ' + name + '<br />' + M.str.moodle.areyousure
                        })
                            .then(function (modal) {
                                modal.setButtonText('save', M.str.moodle.delete);
                                modal.getRoot().on(ModalEvents.save, function (e) {
                                    e.preventDefault();
                                    ajax.call([{
                                        methodname: 'local_wsmanager_webservice_delete',
                                        args: {
                                            webserviceid: webserviceid
                                        }
                                    }])[0]
                                        .then(function (response) {
                                            window.location.replace(jQuery('#local_wsmanager_url').val());
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
                })
                .on('click', '.local_wsmanager_webservice_user_delete', function (event) {
                    event.preventDefault();
                    var $this = jQuery(this),
                        webserviceid = parseInt($this.data('webserviceid')),
                        userid = $this.data('userid');

                    if (webserviceid && userid) {
                        ModalFactory.create({
                            type: ModalFactory.types.SAVE_CANCEL,
                            title: M.str.admin.deleteuser,
                            body: M.str.moodle.areyousure
                        })
                            .then(function (modal) {
                                modal.setButtonText('save', M.str.moodle.delete);
                                modal.getRoot().on(ModalEvents.save, function (e) {
                                    e.preventDefault();
                                    ajax.call([{
                                        methodname: 'local_wsmanager_webservice_user_delete',
                                        args: {
                                            webserviceid: webserviceid,
                                            userid: userid
                                        }
                                    }])[0]
                                        .then(function (response) {
                                            var cnt = jQuery('.local_wsmanager_webservice_user_row_' +
                                                webserviceid).length;
                                            if (response) {
                                                jQuery('#local_wsmanager_webservice_user_row_' +
                                                    webserviceid + '_' + userid).remove();
                                                if (cnt === 1) {
                                                    jQuery('#local_wsmanager_webservice_users_table_' + webserviceid)
                                                        .remove();
                                                }
                                            }
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
        }
    };
});