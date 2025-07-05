/**
 * Toggle component for Moodle 3.1 compatibility
 *
 * @module     local_wsmanager/toggle
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function ($) {
    'use strict';

    /**
     * Initialize toggle functionality
     */
    var init = function () {
        // Handle toggle changes
        $(document).on('change', '.local-wsmanager-toggle-input', function () {
            var $toggle = $(this);
            var $container = $toggle.closest('.local-wsmanager-toggle');

            // Trigger custom event
            $container.trigger('toggle:changed', {
                checked: $toggle.is(':checked'),
                value: $toggle.val(),
                name: $toggle.attr('name'),
                id: $toggle.attr('id')
            });
        });

        // Handle click on label (for better UX)
        $(document).on('click', '.local-wsmanager-toggle-label', function (e) {
            var $label = $(this);
            var $input = $label.prev('.local-wsmanager-toggle-input');

            if (!$input.is(':disabled')) {
                e.preventDefault();
                $input.prop('checked', !$input.is(':checked')).trigger('change');
            }
        });
    };

    /**
     * Create a toggle element
     * @param {Object} options Toggle options
     * @returns {jQuery} jQuery object of the toggle element
     */
    var create = function (options) {
        var defaults = {
            id: 'toggle_' + Math.random().toString(36).substr(2, 9),
            name: '',
            checked: false,
            disabled: false,
            label: '',
            classes: '',
            attributes: ''
        };

        var settings = $.extend({}, defaults, options);

        var html = '<div class="local-wsmanager-toggle ' + settings.classes + '" ' + settings.attributes + '>' +
            '<input type="checkbox" id="' + settings.id + '" name="' + settings.name + '" ' +
            'class="local-wsmanager-toggle-input" value="1" ' +
            (settings.checked ? 'checked' : '') + ' ' +
            (settings.disabled ? 'disabled' : '') + '>' +
            '<label for="' + settings.id + '" class="local-wsmanager-toggle-label">' +
            '<span class="local-wsmanager-toggle-switch"></span>' +
            (settings.label ? '<span class="local-wsmanager-toggle-text">' + settings.label + '</span>' : '') +
            '</label>' +
            '</div>';

        return $(html);
    };

    /**
     * Set toggle state
     * @param {jQuery|string} toggle Toggle element or selector
     * @param {boolean} checked Whether to check the toggle
     */
    var setState = function (toggle, checked) {
        var $toggle = typeof toggle === 'string' ? $(toggle) : toggle;
        var $input = $toggle.find('.local-wsmanager-toggle-input');
        $input.prop('checked', checked).trigger('change');
    };

    /**
     * Get toggle state
     * @param {jQuery|string} toggle Toggle element or selector
     * @returns {boolean} Toggle state
     */
    var getState = function (toggle) {
        var $toggle = typeof toggle === 'string' ? $(toggle) : toggle;
        var $input = $toggle.find('.local-wsmanager-toggle-input');
        return $input.is(':checked');
    };

    /**
     * Enable/disable toggle
     * @param {jQuery|string} toggle Toggle element or selector
     * @param {boolean} enabled Whether to enable the toggle
     */
    var setEnabled = function (toggle, enabled) {
        var $toggle = typeof toggle === 'string' ? $(toggle) : toggle;
        var $input = $toggle.find('.local-wsmanager-toggle-input');
        $input.prop('disabled', !enabled);
    };

    return {
        init: init,
        create: create,
        setState: setState,
        getState: getState,
        setEnabled: setEnabled
    };
});
