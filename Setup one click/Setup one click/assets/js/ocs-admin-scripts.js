/**
 * JavaScript for the admin interface.
 *
 * FILE: assets/js/ocs-admin-scripts.js
 * 
 * CONFLICT PREVENTION:
 * - Wrapped in IIFE (Immediately Invoked Function Expression) to avoid polluting global scope
 * - jQuery is passed as $ to ensure compatibility
 * - All DOM queries use ocs-prefixed selectors
 * - AJAX actions use 'set_up_one_click_' prefix
 * - Local variables are function-scoped, not global
 * 
 * This prevents conflicts with other plugins and themes using jQuery or similar patterns.
 */
 (function($) {
    'use strict';

    let taskQueue = [];
    let totalTasks = 0;
    const i18n = set_up_one_click_ajax_object.i18n;

    $(function() {
        // Handle theme selection visibility
        $('.ocs-radio-image input').on('change', function() {
            $('.ocs-radio-image').removeClass('ocs-radio-image-selected');
            if ($(this).is(':checked')) {
                $(this).closest('.ocs-radio-image').addClass('ocs-radio-image-selected');
            }
        });
        $('.ocs-radio-image input:checked').trigger('change');

        // Handle Plugin Search
        let searchTimeout;
        $('#ocs-plugin-search').on('keyup', function() {
            clearTimeout(searchTimeout);
            const searchTerm = $(this).val().trim();
            const $clearBtn = $('#ocs-clear-search');
            const $resultsContainer = $('#ocs-search-results');

            if (searchTerm.length === 0) {
                $resultsContainer.hide();
                $clearBtn.hide();
                return;
            }

            $clearBtn.show();

            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: set_up_one_click_ajax_object.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'set_up_one_click_search_plugins',
                        nonce: set_up_one_click_ajax_object.nonce,
                        search: searchTerm
                    },
                    success: function(response) {
                        if (response.success && response.data.results.length > 0) {
                            let html = '<div class="ocs-search-results-header">' + i18n.found + ' ' + response.data.results.length + ' ' + (response.data.results.length === 1 ? 'plugin' : 'plugins') + '</div>';
                            
                            response.data.results.forEach(function(plugin) {
                                const isChecked = $('input[name="' + plugin.category + '[]"][value="' + plugin.slug + '"]').is(':checked');
                                html += '<div class="ocs-search-result-item' + (isChecked ? ' ocs-result-checked' : '') + '">';
                                html += '<strong class="ocs-result-name">' + highlightText(plugin.name, searchTerm) + '</strong>';
                                html += '<div class="ocs-result-category">' + plugin.category_display + '</div>';
                                html += '<button type="button" class="ocs-result-select" data-slug="' + plugin.slug + '" data-category="' + plugin.category + '">' + (isChecked ? 'Added' : 'Add') + '</button>';
                                html += '</div>';
                            });

                            $resultsContainer.html(html).show();

                            // Bind click handlers for result items
                            $('.ocs-result-select').off('click').on('click', function(e) {
                                e.preventDefault();
                                const $btn = $(this);
                                const slug = $btn.data('slug');
                                const category = $btn.data('category');
                                const $checkbox = $('input[name="' + category + '[]"][value="' + slug + '"]');
                                
                                $checkbox.prop('checked', !$checkbox.is(':checked'));
                                $btn.closest('.ocs-search-result-item').toggleClass('ocs-result-checked');
                                $btn.text($checkbox.is(':checked') ? 'Added' : 'Add');
                            });
                        } else {
                            $resultsContainer.html('<div class="ocs-search-no-results">' + i18n.no_plugins_found + '</div>').show();
                        }
                    },
                    error: function() {
                        $resultsContainer.html('<div class="ocs-search-error">' + i18n.search_error + '</div>').show();
                    }
                });
            }, 300);
        });

        // Clear search
        $('#ocs-clear-search').on('click', function() {
            $('#ocs-plugin-search').val('').focus();
            $('#ocs-search-results').hide();
            $(this).hide();
        });

        // Close search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.ocs-search-container, .ocs-search-results').length) {
                $('#ocs-search-results').fadeOut(200);
            }
        });

        function highlightText(text, searchTerm) {
            const regex = new RegExp('(' + searchTerm + ')', 'gi');
            return text.replace(regex, '<mark>$1</mark>');
        }

        // Handle Save Recipe
        $('#ocs-recipe-form').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $notice = $('#ocs-ajax-notice');
            const $button = $('#ocs-save-recipe-btn');

            $.ajax({
                url: set_up_one_click_ajax_object.ajax_url,
                type: 'POST',
                data: { action: 'set_up_one_click_save_recipe', nonce: set_up_one_click_ajax_object.nonce, form_data: $form.serialize() },
                beforeSend: function() {
                    $button.prop('disabled', true);
                    $notice.text(i18n.saving).removeClass('notice-error notice-success').addClass('notice-info').show();
                },
                success: function(response) {
                    const message = response.success ? response.data.message : i18n.error_prefix + response.data.message;
                    const noticeClass = response.success ? 'notice-success' : 'notice-error';
                    $notice.text(message).removeClass('notice-info notice-error notice-success').addClass(noticeClass);
                },
                complete: function() {
                    $button.prop('disabled', false);
                    setTimeout(() => $notice.fadeOut(), 4000);
                }
            });
        });
        
        // This JS-based export handles exporting the CURRENT state of the form, which differs from the PHP which exports the SAVED state.
        // We are leaving this as is to preserve existing functionality.
        $('#ocs-export-btn').on('click', function(e) {
            // Check if the click is from the link itself and not a programmatic trigger
            if (e.originalEvent) {
                e.preventDefault(); 
    
                const $form = $('#ocs-recipe-form');
                let recipeData = {
                    'cleanup': [],
                    'settings': [],
                    'content': [],
                    'plugins': [],
                    'code_snippets': [],
                    'utilities': [],
                    'builders': [],
                    'builder_addons': [],
                    'community': [],
                    'import_system': [],
                    'forms': [],
                    'performance': [],
                    'filters': [],
                    'miscellaneous': [],
                    'login_register': [],
                    'lms': [],
                    'memberships': [],
                    'multilingual': [],
                    'seo plugins': [],
                    'Backup plugins': [],
                    'Booking plugins': [],
                    'others': [],
                    'permalink': $form.find('input[name="permalink"]:checked').val() || '',
                    'theme': $form.find('input[name="theme"]:checked').val() || ''
                };
    
                // Manually loop through checkboxes to build clean arrays.
                $form.find('input[name="cleanup[]"]:checked').each(function() {
                    recipeData.cleanup.push($(this).val());
                });
                $form.find('input[name="settings[]"]:checked').each(function() {
                    recipeData.settings.push($(this).val());
                });
                $form.find('input[name="content[]"]:checked').each(function() {
                    recipeData.content.push($(this).val());
                });
                $form.find('input[name="plugins[]"]:checked').each(function() {
                    recipeData.plugins.push($(this).val());
                });
                
                // Add all plugin categories
                const pluginCategories = ['code_snippets', 'utilities', 'builders', 'builder_addons', 'community', 'import_system', 'forms', 'performance', 'filters', 'miscellaneous', 'login_register', 'lms', 'memberships', 'multilingual', 'seo plugins', 'Backup plugins', 'Booking plugins', 'others'];
            pluginCategories.forEach(function(category) {
                $form.find('input[name="' + category + '[]"]:checked').each(function() {
                    recipeData[category].push($(this).val());
                });
            });
    
                const jsonString = JSON.stringify(recipeData, null, 2);
                const blob = new Blob([jsonString], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
    
                const a = document.createElement('a');
                a.href = url;
                a.download = 'set-up-one-click-recipe.json';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        });

        // Handle Deploy Recipe
        $('#ocs-deploy-recipe-btn').on('click', function() {
            if ( $('input[name="plugins[]"]:checked').length > 5 ) {
                alert(i18n.plugin_limit_exceeded);
                return;
            }
            if (!confirm(i18n.confirm_deploy)) return;
            
            buildTaskQueue();
            
            if (taskQueue.length > 0) {
                $('#ocs-progress-modal').fadeIn();
                $('#ocs-live-log').empty();
                $('.ocs-actions .button').prop('disabled', true);
                processNextTask();
            } else {
                alert(i18n.no_tasks);
            }
        });
        
        // Handle Import
        $('#ocs-import-btn').on('click', function() {
            $('#ocs-import-file').click();
        });
        
        $('#ocs-import-file').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) {
                return;
            }

            const formData = new FormData();
            formData.append('action', 'set_up_one_click_import_recipe');
            formData.append('nonce', set_up_one_click_ajax_object.nonce);
            formData.append('import_file', file);

            $.ajax({
                url: set_up_one_click_ajax_object.ajax_url,
                type: 'POST',
                data: formData,
                processData: false, 
                contentType: false, 
                success: function(response) {
                    if (response.success) {
                        alert(i18n.import_success);
                        location.reload();
                    } else {
                        alert(i18n.import_error);
                    }
                },
                error: function() {
                    alert(i18n.import_error);
                }
            });
        });

        function buildTaskQueue() {
            taskQueue = [];
            const $form = $('#ocs-recipe-form');

            $form.find('input[name="cleanup[]"]:checked').each(function() { taskQueue.push({ task: 'cleanup', value: $(this).val() }); });
            $form.find('input[name="settings[]"]:checked').each(function() { taskQueue.push({ task: 'setting', value: $(this).val() }); });
            $form.find('input[name="content[]"]:checked').each(function() { taskQueue.push({ task: 'content', value: $(this).val() }); });
            
            const permalink = $form.find('input[name="permalink"]:checked').val();
            if (permalink) { taskQueue.push({ task: 'permalink', value: permalink }); }
            
            const theme = $form.find('input[name="theme"]:checked').val();
            if (theme) { taskQueue.push({ task: 'install_theme', value: theme }); }

            // Add plugins from all categories
            const pluginCategories = ['plugins', 'code_snippets', 'utilities', 'builders', 'builder_addons', 'community', 'import_system', 'forms', 'performance', 'filters', 'miscellaneous', 'login_register', 'lms', 'memberships', 'multilingual', 'seo plugins', 'Backup plugins', 'Booking plugins', 'others'];
            
            pluginCategories.forEach(function(category) {
                $form.find('input[name="' + category + '[]"]:checked').each(function() {
                    taskQueue.push({ task: 'install_plugin', value: $(this).val() });
                    taskQueue.push({ task: 'activate_plugin', value: $(this).val() });
                });
            });
            
            totalTasks = taskQueue.length;
        }

        function processNextTask() {
            if (taskQueue.length === 0) {
                // All regular tasks complete, now check for bulk content
                const bulkData = getBulkContentData();
                
                if (Object.keys(bulkData).length > 0) {
                    // We have bulk content to create
                    createBulkContent(bulkData);
                } else {
                    // No bulk content, deployment is complete
                    completeDeployment();
                }
                return;
            }

            const task = taskQueue.shift();
            
            $.ajax({
                url: set_up_one_click_ajax_object.ajax_url,
                type: 'POST',
                data: { action: 'set_up_one_click_execute_task', nonce: set_up_one_click_ajax_object.nonce, task_details: task },
                success: function(response) {
                    const icon = response.success ? '<span class="dashicons dashicons-yes-alt"></span>' : '<span class="dashicons dashicons-dismiss"></span>';
                    updateLog(icon + ' ' + response.data.message);
                    updateProgress();
                    
                    if (response.success) {
                        processNextTask();
                    } else {
                        const errorIcon = '<span class="dashicons dashicons-warning"></span>';
                        updateLog(errorIcon + ' ' + i18n.deployment_halted_error);
                        $('.ocs-modal-title').html(errorIcon + ' ' + i18n.deployment_halted_error);
                        $('.ocs-modal-subtitle').html(
                            $('<a>').attr({href: '#', id: 'ocs-close-modal'}).text(i18n.you_may_close)
                        );
                    }
                },
                error: function(xhr) {
                    const errorIcon = '<span class="dashicons dashicons-warning"></span>';
                    let errorText = xhr.responseText || (xhr.status + ' ' + xhr.statusText);
                    updateLog(errorIcon + ' ' + i18n.ajax_error_prefix + errorText);
                    updateLog(errorIcon + ' ' + i18n.server_error);
                    $('.ocs-modal-title').html(errorIcon + ' ' + i18n.server_error);
                     $('.ocs-modal-subtitle').html(
                        $('<a>').attr({href: '#', id: 'ocs-close-modal'}).text(i18n.you_may_close)
                    );
                }
            });
        }

        function createBulkContent(bulkData) {
            const progressIcon = '<span class="dashicons dashicons-plus-alt"></span>';
            updateLog(progressIcon + ' Creating bulk content...');

            $.ajax({
                url: set_up_one_click_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'set_up_one_click_create_bulk_content',
                    nonce: set_up_one_click_ajax_object.nonce,
                    bulk_data: JSON.stringify(bulkData)
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const icon = '<span class="dashicons dashicons-yes-alt"></span>';
                        updateLog(icon + ' Bulk content created successfully!');
                        if (response.data.results) {
                            Object.entries(response.data.results).forEach(function([type, result]) {
                                if (result.error) {
                                    updateLog('  ✗ ' + type + ': ' + result.error);
                                } else {
                                    const count = Object.values(result).filter(r => r.success).length;
                                    updateLog('  ✓ ' + type + ': ' + count + ' item(s) created');
                                }
                            });
                        }
                        completeDeployment();
                    } else {
                        const errorIcon = '<span class="dashicons dashicons-warning"></span>';
                        updateLog(errorIcon + ' Error creating bulk content: ' + response.data.message);
                        completeDeployment();
                    }
                },
                error: function(xhr) {
                    const errorIcon = '<span class="dashicons dashicons-warning"></span>';
                    let errorText = xhr.responseText || (xhr.status + ' ' + xhr.statusText);
                    updateLog(errorIcon + ' AJAX Error: ' + errorText);
                    completeDeployment();
                }
            });
        }

        function completeDeployment() {
            const successIcon = '<span class="dashicons dashicons-yes-alt"></span>';
            updateLog(successIcon + ' ' + i18n.all_tasks_complete);
            $('.ocs-modal-title').html(successIcon + ' ' + i18n.all_tasks_complete);
            // Set review prompt flag once per successful run
            try {
                const fd = new FormData();
                fd.append('action','set_up_one_click_set_review_prompt');
                fd.append('nonce', set_up_one_click_ajax_object.nonce);
                fetch(set_up_one_click_ajax_object.ajax_url, { method: 'POST', credentials: 'same-origin', body: fd });
            } catch (e) {}
            $('.ocs-modal-subtitle').html($('<a>').attr({href: '#', id: 'ocs-close-modal'}).text(i18n.you_may_close));
            // Set progress to 100%
            $('#ocs-progress-bar').css('width', '100%');
            $('#ocs-progress-text').text('100%');
        }
        
        $(document).on('click', '#ocs-close-modal', function(e) {
            e.preventDefault();
            $('#ocs-progress-modal').fadeOut();
            location.reload();
        });

        function updateProgress() {
            const tasksCompleted = totalTasks - taskQueue.length;
            const percentage = totalTasks > 0 ? (tasksCompleted / totalTasks) * 100 : 0;
            $('#ocs-progress-bar').css('width', percentage + '%');
            $('#ocs-progress-text').text(Math.round(percentage) + '%');
        }

        function updateLog(message) {
            const $log = $('#ocs-live-log');
            $log.append('<div>' + message + '</div>').scrollTop($log[0].scrollHeight);
        }

        // Bulk Content Toggle and Field Display with Cascading Plugin/Course Selection
        $('.ocs-bulk-toggle-checkbox').on('change', function() {
            const type = $(this).data('type');
            const $checkbox = $(this);

            // Get all selected types
            const selectedTypes = $('.ocs-bulk-toggle-checkbox:checked').map(function() {
                return $(this).data('type');
            }).get();

            // Show/hide the main bulk content fields container
            if (selectedTypes.length > 0) {
                $('#ocs-bulk-content-fields').show();
                
                // Show/hide specific sections based on type
                if (selectedTypes.includes('courses')) {
                    $('#ocs-courses-section').show();
                    loadLmsPlugins();
                } else {
                    $('#ocs-courses-section').hide();
                }
                
                if (selectedTypes.includes('memberships')) {
                    $('#ocs-memberships-section').show();
                    loadMembershipPlugins();
                } else {
                    $('#ocs-memberships-section').hide();
                }
                
                // Load standard content fields if pages, posts, products or comments are selected
                if (selectedTypes.some(t => ['pages', 'posts', 'products', 'comments'].includes(t))) {
                    loadStandardContentFields(selectedTypes.filter(t => ['pages', 'posts', 'products', 'comments'].includes(t)));
                } else {
                    $('#ocs-standard-content-fields').html('');
                }
            } else {
                $('#ocs-bulk-content-fields').hide();
            }
        });

        // Load LMS Plugins
        function loadLmsPlugins() {
            $.ajax({
                url: set_up_one_click_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'set_up_one_click_get_lms_plugins',
                    nonce: set_up_one_click_ajax_object.nonce
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const activePlugins = response.data.active_plugins;
                        let html = '';
                        
                        if (Object.keys(activePlugins).length === 0) {
                            html = '<p style="color: #d32f2f;">' + set_up_one_click_ajax_object.i18n.no_lms_plugins + '</p>';
                        } else {
                            html = '<div class="ocs-plugin-selection-grid">';
                            Object.entries(activePlugins).forEach(function([key, plugin]) {
                                html += '<label class="ocs-plugin-selection-item">';
                                html += '<input type="radio" name="ocs_selected_lms_plugin" value="' + key + '" class="ocs-lms-plugin-radio" data-plugin-name="' + plugin.name + '" />';
                                html += '<span class="dashicons ' + plugin.icon + '"></span>';
                                html += '<strong>' + plugin.name + '</strong>';
                                html += '<p>' + plugin.description + '</p>';
                                html += '</label>';
                            });
                            html += '</div>';
                        }
                        
                        $('#ocs-lms-plugins-list').html(html);
                        
                        // Bind click handler for plugin selection
                        $(document).off('change', '.ocs-lms-plugin-radio').on('change', '.ocs-lms-plugin-radio', function() {
                            const pluginKey = $(this).val();
                            const pluginName = $(this).data('plugin-name');
                            loadCoursesForPlugin(pluginKey, pluginName);
                        });
                    }
                },
                error: function() {
                    $('#ocs-lms-plugins-list').html('<p style="color: #d32f2f;">Error loading LMS plugins</p>');
                }
            });
        }

        // Load Courses for Selected LMS Plugin
        function loadCoursesForPlugin(pluginKey, pluginName) {
            $.ajax({
                url: set_up_one_click_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'set_up_one_click_get_courses_for_lms',
                    nonce: set_up_one_click_ajax_object.nonce,
                    plugin_key: pluginKey
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const courses = response.data.courses;
                        let html = '';
                        
                        // Update the label to show selected plugin
                        $('#ocs-selected-lms-label').text('Courses for ' + pluginName);
                        
                        if (Object.keys(courses).length === 0) {
                            html = '<p style="color: #d32f2f;">No courses available for this plugin</p>';
                        } else {
                            html = '<div class="ocs-courses-selection-grid ocs-bulk-items-grid">';
                            Object.entries(courses).forEach(function([key, course]) {
                                html += '<div class="ocs-course-item ocs-bulk-item">';
                                html += '<label>';
                                html += '<input type="checkbox" name="ocs_selected_courses[]" value="' + key + '" />';
                                html += '<div class="ocs-course-info ocs-bulk-item-text">';
                                html += '<strong>' + course.title + '</strong>';
                                html += '<p class="ocs-course-desc">' + course.description + '</p>';
                                html += '<small>Price: $' + course.price + ' | Lessons: ' + course.lessons + '</small>';
                                html += '</div>';
                                html += '</label>';
                                html += '</div>';
                            });
                            html += '</div>';
                        }
                        
                        $('#ocs-lms-courses-list').html(html);
                        $('#ocs-courses-selection-step').show();
                    }
                },
                error: function() {
                    $('#ocs-lms-courses-list').html('<p style="color: #d32f2f;">Error loading courses</p>');
                    $('#ocs-courses-selection-step').hide();
                }
            });
        }

        // Load Membership Plugins
        function loadMembershipPlugins() {
            $.ajax({
                url: set_up_one_click_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'set_up_one_click_get_membership_plugins',
                    nonce: set_up_one_click_ajax_object.nonce
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const activePlugins = response.data.active_plugins;
                        let html = '';
                        
                        if (Object.keys(activePlugins).length === 0) {
                            html = '<p style="color: #d32f2f;">' + set_up_one_click_ajax_object.i18n.no_membership_plugins + '</p>';
                        } else {
                            html = '<div class="ocs-plugin-selection-grid">';
                            Object.entries(activePlugins).forEach(function([key, plugin]) {
                                html += '<label class="ocs-plugin-selection-item">';
                                html += '<input type="radio" name="ocs_selected_membership_plugin" value="' + key + '" class="ocs-membership-plugin-radio" data-plugin-name="' + plugin.name + '" />';
                                html += '<span class="dashicons ' + plugin.icon + '"></span>';
                                html += '<strong>' + plugin.name + '</strong>';
                                html += '<p>' + plugin.description + '</p>';
                                html += '</label>';
                            });
                            html += '</div>';
                        }
                        
                        $('#ocs-membership-plugins-list').html(html);
                        
                        // Bind click handler for plugin selection
                        $(document).off('change', '.ocs-membership-plugin-radio').on('change', '.ocs-membership-plugin-radio', function() {
                            const pluginKey = $(this).val();
                            const pluginName = $(this).data('plugin-name');
                            loadMembershipsForPlugin(pluginKey, pluginName);
                        });
                    }
                },
                error: function() {
                    $('#ocs-membership-plugins-list').html('<p style="color: #d32f2f;">Error loading membership plugins</p>');
                }
            });
        }

        // Load Memberships for Selected Plugin
        function loadMembershipsForPlugin(pluginKey, pluginName) {
            $.ajax({
                url: set_up_one_click_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'set_up_one_click_get_memberships_for_plugin',
                    nonce: set_up_one_click_ajax_object.nonce,
                    plugin_key: pluginKey
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const memberships = response.data.memberships;
                        let html = '';
                        
                        // Update the label to show selected plugin
                        $('#ocs-selected-membership-label').text('Memberships for ' + pluginName);
                        
                        if (Object.keys(memberships).length === 0) {
                            html = '<p style="color: #d32f2f;">No memberships available for this plugin</p>';
                        } else {
                            html = '<div class="ocs-memberships-selection-grid ocs-bulk-items-grid">';
                            Object.entries(memberships).forEach(function([key, membership]) {
                                html += '<div class="ocs-membership-item ocs-bulk-item">';
                                html += '<label>';
                                html += '<input type="checkbox" name="ocs_selected_memberships[]" value="' + key + '" />';
                                html += '<div class="ocs-membership-info ocs-bulk-item-text">';
                                html += '<strong>' + membership.title + '</strong>';
                                html += '<p class="ocs-membership-desc">' + membership.description + '</p>';
                                html += '<small>Price: $' + membership.price + ' | Duration: ' + membership.duration + '</small>';
                                html += '</div>';
                                html += '</label>';
                                html += '</div>';
                            });
                            html += '</div>';
                        }
                        
                        $('#ocs-membership-items-list').html(html);
                        $('#ocs-memberships-selection-step').show();
                    }
                },
                error: function() {
                    $('#ocs-membership-items-list').html('<p style="color: #d32f2f;">Error loading memberships</p>');
                    $('#ocs-memberships-selection-step').hide();
                }
            });
        }

        // Load Standard Content Fields
        function loadStandardContentFields(types) {
            $.ajax({
                url: set_up_one_click_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'set_up_one_click_get_bulk_templates',
                    nonce: set_up_one_click_ajax_object.nonce,
                    types: JSON.stringify(types)
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data.templates) {
                        let html = '';
                        Object.entries(response.data.templates).forEach(function([type, templates]) {
                            if (templates && Object.keys(templates).length > 0) {
                                html += generateBulkFieldHTML(type, templates, null);
                            }
                        });
                        $('#ocs-standard-content-fields').html(html);
                    } else {
                        $('#ocs-standard-content-fields').html('');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error loading templates:', error);
                    $('#ocs-standard-content-fields').html('');
                }
            });
        }

        function generateBulkFieldHTML(type, templates, pluginStatus) {
            let typeLabel = {
                'pages': 'Pages',
                'posts': 'Posts',
                'products': 'Products',
                'comments': 'Comments',
                'courses': 'Courses',
                'memberships': 'Memberships'
            }[type] || type;

            let typeIcon = {
                'pages': 'dashicons-admin-page',
                'posts': 'dashicons-format-aside',
                'products': 'dashicons-cart',
                'comments': 'dashicons-admin-comments',
                'courses': 'dashicons-book-alt',
                'memberships': 'dashicons-groups'
            }[type] || 'dashicons-admin-post';

            let html = '<div class="ocs-bulk-field-group">';
            html += '<div class="ocs-bulk-field-title"><span class="dashicons ' + typeIcon + '"></span>' + typeLabel + '</div>';

            if (type === 'comments') {
                html += '<div class="ocs-bulk-comments-option">';
                html += '<label><input type="number" name="bulk_comments_count" class="ocs-bulk-input" min="1" max="20" value="3" /> ' + typeLabel + ' per post</label>';
                html += '</div>';
            } else {
                html += '<div class="ocs-bulk-items-grid">';
                Object.keys(templates).forEach(function(key) {
                    const template = templates[key];
                    let displayName = template.title || template;
                    let content = '';

                    if (typeof template === 'object') {
                        if (template.description) {
                            content = '<div class="ocs-bulk-item-content">' + template.description.substring(0, 50) + '...</div>';
                        } else if (template.content) {
                            content = '<div class="ocs-bulk-item-content">' + template.content.substring(0, 50) + '...</div>';
                        } else if (template.category) {
                            content = '<div class="ocs-bulk-item-content">Category: ' + template.category + '</div>';
                        }
                    }

                    html += '<div class="ocs-bulk-item">';
                    html += '<label>';
                    html += '<input type="checkbox" name="bulk_' + type + '[]" class="ocs-bulk-input" value="' + key + '" />';
                    html += '<div class="ocs-bulk-item-text"><strong>' + displayName + '</strong></div>';
                    html += '</label>';
                    if (template.price) {
                        html += '<small class="ocs-bulk-item-price">$' + template.price + '</small>';
                    }
                    html += content;
                    html += '</div>';
                });
                html += '</div>';
            }

            html += '</div>';
            return html;
        }



        function getBulkContentData() {
            const bulkData = {};

            if ($('input[name="bulk_pages[]"]:checked').length > 0) {
                bulkData.pages = $('input[name="bulk_pages[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
            }

            if ($('input[name="bulk_posts[]"]:checked').length > 0) {
                bulkData.posts = $('input[name="bulk_posts[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
            }

            if ($('input[name="bulk_products[]"]:checked').length > 0) {
                bulkData.products = $('input[name="bulk_products[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
            }

            if ($('input[name="bulk_comments_count"]').val()) {
                bulkData.comments = $('input[name="bulk_comments_count"]').val();
            }

            // Get selected LMS plugin and courses
            const selectedLmsPlugin = $('input[name="ocs_selected_lms_plugin"]:checked').val();
            const selectedCourses = $('input[name="ocs_selected_courses[]"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedLmsPlugin && selectedCourses.length > 0) {
                bulkData.courses = {
                    plugin: selectedLmsPlugin,
                    course_ids: selectedCourses
                };
            }

            // Get selected Membership plugin and memberships
            const selectedMembershipPlugin = $('input[name="ocs_selected_membership_plugin"]:checked').val();
            const selectedMemberships = $('input[name="ocs_selected_memberships[]"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedMembershipPlugin && selectedMemberships.length > 0) {
                bulkData.memberships = {
                    plugin: selectedMembershipPlugin,
                    membership_ids: selectedMemberships
                };
            }
            return bulkData;
        }
    });
})(jQuery);
