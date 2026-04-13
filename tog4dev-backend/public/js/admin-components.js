(function() {
    'use strict';

    var AdminToast = {
        container: null,
        init: function() {
            if (this.container) return;
            this.container = document.createElement('div');
            this.container.id = 'admin-toast-container';
            this.container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:99999;display:flex;flex-direction:column;gap:8px;pointer-events:none;';
            document.body.appendChild(this.container);
        },
        show: function(message, type, duration) {
            this.init();
            type = type || 'info';
            duration = duration || 4000;
            var icons = { success: 'fa-check-circle', error: 'fa-times-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
            var colors = { success: '#10b981', error: '#ef4444', warning: '#f59e0b', info: '#3b82f6' };

            var toast = document.createElement('div');
            toast.style.cssText = 'pointer-events:auto;display:flex;align-items:center;gap:12px;padding:14px 20px;background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,0.12);border-left:4px solid ' + colors[type] + ';min-width:300px;max-width:450px;transform:translateX(120%);transition:transform 0.3s cubic-bezier(0.4,0,0.2,1),opacity 0.3s;opacity:0;font-family:Inter,sans-serif;';
            toast.innerHTML = '<i class="fas ' + icons[type] + '" style="color:' + colors[type] + ';font-size:18px;flex-shrink:0;"></i><span style="font-size:14px;color:#1f2937;flex:1;">' + message + '</span><button style="background:none;border:none;cursor:pointer;padding:4px;color:#9ca3af;font-size:16px;" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>';

            this.container.appendChild(toast);
            requestAnimationFrame(function() {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            });

            setTimeout(function() {
                toast.style.transform = 'translateX(120%)';
                toast.style.opacity = '0';
                setTimeout(function() { if (toast.parentElement) toast.remove(); }, 300);
            }, duration);
        }
    };

    window.AdminToast = AdminToast;

    var CommandPalette = {
        overlay: null,
        input: null,
        results: null,
        commands: [],
        isOpen: false,

        init: function() {
            this.buildUI();
            this.bindKeys();
            this.loadCommands();
        },

        loadCommands: function() {
            var locale = document.documentElement.lang || 'en';
            var prefix = '/' + locale;

            this.commands = [
                { title: 'Dashboard', icon: 'fas fa-tachometer-alt', url: prefix + '/', category: 'Navigation' },
                { title: 'Payments', icon: 'fas fa-credit-card', url: prefix + '/payments', category: 'Navigation' },
                { title: 'Users', icon: 'fas fa-users', url: prefix + '/users', category: 'Navigation' },
                { title: 'News Management', icon: 'fas fa-newspaper', url: prefix + '/news-management', category: 'Content' },
                { title: 'Photos Gallery', icon: 'fas fa-images', url: prefix + '/gallery-management/photos', category: 'Content' },
                { title: 'Videos Gallery', icon: 'fas fa-video', url: prefix + '/gallery-management/videos', category: 'Content' },
                { title: 'Sliders', icon: 'fas fa-sliders-h', url: prefix + '/sliders', category: 'Content' },
                { title: 'Influencers', icon: 'fas fa-hand-holding-heart', url: prefix + '/influencers', category: 'Users' },
                { title: 'Admins', icon: 'fas fa-user-shield', url: prefix + '/admins', category: 'Users' },
                { title: 'Subscriptions (Active)', icon: 'fas fa-sync-alt', url: prefix + '/subscriptions/active', category: 'Business' },
                { title: 'Subscriptions (Inactive)', icon: 'fas fa-pause-circle', url: prefix + '/subscriptions/inactive', category: 'Business' },
                { title: 'Newsletter', icon: 'fas fa-envelope', url: prefix + '/newsletter', category: 'Communications' },
                { title: 'SEO', icon: 'fas fa-search', url: prefix + '/seo', category: 'System' },
                { title: 'Short Links', icon: 'fas fa-link', url: prefix + '/shortlinks', category: 'System' },
                { title: 'Activity Logs', icon: 'fas fa-history', url: prefix + '/system/activity-logs', category: 'System' },
                { title: 'Notifications', icon: 'fas fa-bell', url: prefix + '/system/notifications', category: 'System' },
                { title: 'Settings', icon: 'fas fa-cog', url: prefix + '/system/settings', category: 'System' },
                { title: 'System Health', icon: 'fas fa-heartbeat', url: prefix + '/system/health', category: 'System' },
                { title: 'Reports Center', icon: 'fas fa-chart-pie', url: prefix + '/system/reports', category: 'System' },
                { title: 'Media Library', icon: 'fas fa-photo-video', url: prefix + '/system/media-library', category: 'System' },
                { title: 'Add News', icon: 'fas fa-plus', url: prefix + '/news-management/create', category: 'Quick Actions' },
                { title: 'Add Photo', icon: 'fas fa-plus', url: prefix + '/gallery-management/photos/create', category: 'Quick Actions' },
                { title: 'Add Video', icon: 'fas fa-plus', url: prefix + '/gallery-management/videos/create', category: 'Quick Actions' },
                { title: 'Add Admin', icon: 'fas fa-plus', url: prefix + '/admins/create', category: 'Quick Actions' },
                { title: 'Upload Excel Sheet', icon: 'fas fa-file-excel', url: prefix + '/excel/create', category: 'Quick Actions' },
            ];
        },

        buildUI: function() {
            this.overlay = document.createElement('div');
            this.overlay.id = 'command-palette-overlay';
            this.overlay.style.cssText = 'display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100000;backdrop-filter:blur(4px);justify-content:center;padding-top:15vh;';

            var modal = document.createElement('div');
            modal.style.cssText = 'background:#fff;border-radius:16px;width:560px;max-width:90vw;max-height:70vh;box-shadow:0 25px 60px rgba(0,0,0,0.2);display:flex;flex-direction:column;overflow:hidden;animation:cmdPaletteIn 0.15s ease-out;';

            var header = document.createElement('div');
            header.style.cssText = 'padding:16px 20px;border-bottom:1px solid #e5e7eb;display:flex;align-items:center;gap:12px;';
            header.innerHTML = '<i class="fas fa-search" style="color:#9ca3af;font-size:16px;"></i>';

            this.input = document.createElement('input');
            this.input.type = 'text';
            this.input.placeholder = 'Search pages, actions...';
            this.input.style.cssText = 'flex:1;border:none;outline:none;font-size:16px;font-family:Inter,sans-serif;background:transparent;color:#111827;';

            var kbd = document.createElement('kbd');
            kbd.textContent = 'ESC';
            kbd.style.cssText = 'font-size:11px;padding:2px 6px;border:1px solid #d1d5db;border-radius:4px;color:#6b7280;background:#f9fafb;font-family:Inter,sans-serif;';

            header.appendChild(this.input);
            header.appendChild(kbd);

            this.results = document.createElement('div');
            this.results.style.cssText = 'overflow-y:auto;padding:8px;flex:1;';

            modal.appendChild(header);
            modal.appendChild(this.results);
            this.overlay.appendChild(modal);
            document.body.appendChild(this.overlay);

            var self = this;
            this.overlay.addEventListener('click', function(e) {
                if (e.target === self.overlay) self.close();
            });

            this.input.addEventListener('input', function() {
                self.search(this.value);
            });

            this.input.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') { self.close(); return; }
                if (e.key === 'Enter') {
                    var first = self.results.querySelector('.cmd-item');
                    if (first) window.location.href = first.dataset.url;
                }
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    var items = self.results.querySelectorAll('.cmd-item');
                    var active = self.results.querySelector('.cmd-item.active');
                    var idx = Array.prototype.indexOf.call(items, active);
                    if (e.key === 'ArrowDown') idx = Math.min(idx + 1, items.length - 1);
                    else idx = Math.max(idx - 1, 0);
                    items.forEach(function(i) { i.classList.remove('active'); });
                    if (items[idx]) {
                        items[idx].classList.add('active');
                        items[idx].scrollIntoView({ block: 'nearest' });
                    }
                }
            });
        },

        bindKeys: function() {
            var self = this;
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    self.isOpen ? self.close() : self.open();
                }
                if (e.key === 'Escape' && self.isOpen) {
                    self.close();
                }
            });
        },

        open: function() {
            this.isOpen = true;
            this.overlay.style.display = 'flex';
            this.input.value = '';
            this.search('');
            var self = this;
            setTimeout(function() { self.input.focus(); }, 50);
        },

        close: function() {
            this.isOpen = false;
            this.overlay.style.display = 'none';
        },

        search: function(query) {
            var q = query.toLowerCase().trim();
            var filtered = this.commands;
            if (q) {
                filtered = this.commands.filter(function(cmd) {
                    return cmd.title.toLowerCase().indexOf(q) !== -1 || cmd.category.toLowerCase().indexOf(q) !== -1;
                });
            }

            var grouped = {};
            filtered.forEach(function(cmd) {
                if (!grouped[cmd.category]) grouped[cmd.category] = [];
                grouped[cmd.category].push(cmd);
            });

            var html = '';
            for (var cat in grouped) {
                html += '<div style="padding:4px 12px 4px;"><span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:1px;color:#9ca3af;">' + cat + '</span></div>';
                grouped[cat].forEach(function(cmd, i) {
                    html += '<a href="' + cmd.url + '" class="cmd-item' + (i === 0 && !q ? '' : '') + '" data-url="' + cmd.url + '" style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:8px;text-decoration:none;color:#374151;transition:background 0.1s;cursor:pointer;" onmouseenter="this.style.background=\'#f3f4f6\'" onmouseleave="this.style.background=\'transparent\'">';
                    html += '<i class="' + cmd.icon + '" style="width:20px;text-align:center;color:#6b7280;font-size:14px;"></i>';
                    html += '<span style="font-size:14px;">' + cmd.title + '</span>';
                    html += '</a>';
                });
            }

            if (filtered.length === 0) {
                html = '<div style="text-align:center;padding:40px 20px;"><i class="fas fa-search" style="font-size:32px;color:#d1d5db;display:block;margin-bottom:12px;"></i><p style="color:#6b7280;font-size:14px;margin:0;">No results found</p></div>';
            }

            this.results.innerHTML = html;
        }
    };

    var ConfirmDialog = {
        show: function(options) {
            var defaults = {
                title: 'Are you sure?',
                message: '',
                confirmText: 'Confirm',
                cancelText: 'Cancel',
                type: 'warning',
                onConfirm: function() {},
                onCancel: function() {}
            };
            var opts = Object.assign({}, defaults, options);
            var colors = { warning: '#f59e0b', danger: '#ef4444', info: '#3b82f6', success: '#10b981' };
            var icons = { warning: 'fa-exclamation-triangle', danger: 'fa-trash-alt', info: 'fa-info-circle', success: 'fa-check-circle' };

            var overlay = document.createElement('div');
            overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100001;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(2px);';

            var dialog = document.createElement('div');
            dialog.style.cssText = 'background:#fff;border-radius:16px;padding:32px;max-width:420px;width:90vw;text-align:center;box-shadow:0 25px 60px rgba(0,0,0,0.2);animation:cmdPaletteIn 0.15s ease-out;';
            dialog.innerHTML = '<div style="width:56px;height:56px;border-radius:50%;background:' + colors[opts.type] + '15;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;"><i class="fas ' + icons[opts.type] + '" style="font-size:24px;color:' + colors[opts.type] + ';"></i></div><h5 style="font-size:18px;font-weight:700;margin-bottom:8px;color:#111827;">' + opts.title + '</h5><p style="font-size:14px;color:#6b7280;margin-bottom:24px;">' + opts.message + '</p><div style="display:flex;gap:12px;justify-content:center;"><button class="cmd-cancel" style="padding:10px 24px;border:1px solid #d1d5db;background:#fff;border-radius:8px;font-size:14px;cursor:pointer;color:#374151;font-family:Inter,sans-serif;">' + opts.cancelText + '</button><button class="cmd-confirm" style="padding:10px 24px;border:none;background:' + colors[opts.type] + ';color:#fff;border-radius:8px;font-size:14px;cursor:pointer;font-family:Inter,sans-serif;font-weight:600;">' + opts.confirmText + '</button></div>';

            overlay.appendChild(dialog);
            document.body.appendChild(overlay);

            dialog.querySelector('.cmd-cancel').addEventListener('click', function() { overlay.remove(); opts.onCancel(); });
            dialog.querySelector('.cmd-confirm').addEventListener('click', function() { overlay.remove(); opts.onConfirm(); });
            overlay.addEventListener('click', function(e) { if (e.target === overlay) { overlay.remove(); opts.onCancel(); } });
        }
    };

    window.AdminConfirm = ConfirmDialog;

    function enhanceDeleteButtons() {
        document.querySelectorAll('.btn-delete, [data-action="delete"]').forEach(function(btn) {
            if (btn.dataset.enhanced) return;
            btn.dataset.enhanced = 'true';
            btn.addEventListener('click', function(e) {
                var original = btn.onclick;
                if (!btn.dataset.confirmOverride) return;
                e.preventDefault();
                e.stopPropagation();
                AdminConfirm.show({
                    title: 'Delete this item?',
                    message: 'This action cannot be undone.',
                    type: 'danger',
                    confirmText: 'Delete',
                    onConfirm: function() {
                        if (original) original.call(btn, e);
                    }
                });
            });
        });
    }

    function addKeyboardShortcutHints() {
        var sidebar = document.querySelector('#side-menu');
        if (!sidebar) return;

        var shortcutHint = document.createElement('div');
        shortcutHint.style.cssText = 'padding:12px 20px;border-top:1px solid rgba(255,255,255,0.1);margin-top:auto;';
        shortcutHint.innerHTML = '<div style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:8px 12px;border-radius:8px;background:rgba(255,255,255,0.05);" onclick="if(window.CommandPalette)CommandPalette.open()"><i class="fas fa-search" style="color:rgba(255,255,255,0.5);font-size:12px;"></i><span style="color:rgba(255,255,255,0.5);font-size:12px;">Search</span><kbd style="margin-left:auto;font-size:10px;padding:1px 5px;border:1px solid rgba(255,255,255,0.2);border-radius:3px;color:rgba(255,255,255,0.4);background:rgba(255,255,255,0.05);">Ctrl+K</kbd></div>';

        var sidebarContainer = sidebar.closest('.left-side-menu');
        if (sidebarContainer) {
            var simplebar = sidebarContainer.querySelector('[data-simplebar]');
            if (simplebar) simplebar.appendChild(shortcutHint);
        }
    }

    var style = document.createElement('style');
    style.textContent = '@keyframes cmdPaletteIn{from{opacity:0;transform:scale(0.95) translateY(-10px)}to{opacity:1;transform:scale(1) translateY(0)}}.cmd-item.active{background:#f3f4f6 !important;}';
    document.head.appendChild(style);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            CommandPalette.init();
            window.CommandPalette = CommandPalette;
            enhanceDeleteButtons();
            addKeyboardShortcutHints();
        });
    } else {
        CommandPalette.init();
        window.CommandPalette = CommandPalette;
        enhanceDeleteButtons();
        addKeyboardShortcutHints();
    }

})();
