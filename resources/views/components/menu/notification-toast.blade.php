@props([
    'type' => session()->has('error') ? 'error' : 'success', // success | error
    'title' => session('success.title') ?? session('error.title') ?? 'Message Saved!',
    'message' => session('success.message') ?? session('error.message') ?? 'The message will be sent in 5 minutes.',
    'triggerId' => 'toast-trigger',
    'showButton' => false,
    'buttonText' => 'Show Toast',
    'autoHideMs' => 2000,
])

@php
    $isSuccess = $type === 'success';
    $colorClass = $isSuccess ? '[--color:var(--color-success)]' : '[--color:var(--color-danger)]';
    $toastUid = 'toast-'.uniqid();
@endphp

<div class="flex justify-end">
    <div id="{{ $toastUid }}-stack" data-menu-toast-stack="1" data-menu-toast-uid="{{ $toastUid }}" data-menu-toast-timeout="{{ (int) ($autoHideMs ?? 2000) }}" class="pointer-events-none fixed z-[60] top-12 right-4 flex flex-col gap-2"></div>
    <template id="{{ $toastUid }}-template" data-menu-toast-template="1" data-menu-toast-uid="{{ $toastUid }}">
        <div class="pointer-events-auto max-w-sm rounded-md border border-foreground/10 bg-background px-4 py-3 shadow-[0px_3px_5px_#0000000b] transform transition-all duration-300 ease-out opacity-0 translate-y-2 overflow-hidden" style="will-change: transform, opacity, height;">
            <div class="flex items-start">
                <span class="ml-2 mr-3 mt-1 size-6 stroke-[.8] {{ $colorClass }} flex-shrink-0">
                    @if ($isSuccess)
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle size-6 stroke-(--color) fill-(--color)/25"><path d="M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20"></path><path d="m9 12 2 2 4-4"></path></svg>
                    @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x-circle" class="lucide lucide-x-circle size-6 stroke-(--color) fill-(--color)/25"><circle cx="12" cy="12" r="10"></circle><path d="m15 9-6 6"></path><path d="m9 9 6 6"></path></svg>
                    @endif
                </span>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm mb-1">{{ $title }}</div>
                    <div class="opacity-70 text-sm leading-relaxed break-words">{{ $message }}</div>
                </div>
                <button type="button" class="ml-2 text-xs opacity-70 close-btn flex-shrink-0 mt-1">Close</button>
            </div>
        </div>
    </template>
    @if ($showButton)
    <a id="{{ $triggerId }}" href="#" class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-sm border shadow-sm bg-primary/10 border-primary/20 text-primary hover:bg-primary/15">
        <span class="size-5 {{ $colorClass }}">
            @if ($isSuccess)
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle size-5 stroke-(--color) fill-(--color)/25"><path d="M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20"></path><path d="m9 12 2 2 4-4"></path></svg>
            @else
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x-circle" class="lucide lucide-x-circle size-5 stroke-(--color) fill-(--color)/25"><circle cx="12" cy="12" r="10"></circle><path d="m15 9-6 6"></path><path d="m9 9 6 6"></path></svg>
            @endif
        </span>
        {{ $buttonText }}
    </a>
    @endif
    @push('scripts')
    <script>
        (function(){
            function setup(){
                try {
                    var trigger = document.getElementById(@json($triggerId));
                    var stack = document.getElementById(@json($toastUid . '-stack'));
                    var tmpl = document.getElementById(@json($toastUid . '-template'));
                    var timeout = Number(@json($autoHideMs));
                    if (stack && tmpl) {
                        // Build toast node with optional overrides (template argument)
                        function buildToastNode(template, options) {
                            var opts = options || {};
                            var node = template.content.firstElementChild.cloneNode(true);
                            var titleEl = node.querySelector('.font-semibold');
                            var msgEl = node.querySelector('.opacity-70');
                            if (titleEl && typeof opts.title === 'string' && opts.title.length) titleEl.textContent = opts.title;
                            if (msgEl && typeof opts.message === 'string' && opts.message.length) msgEl.textContent = opts.message;
                            var type = (opts.type === 'error' || opts.type === 'success') ? opts.type : null;
                            if (type) {
                                var iconWrap = node.querySelector('span');
                                if (iconWrap) {
                                    if (type === 'error') {
                                        iconWrap.classList.remove('[--color:var(--color-success)]');
                                        iconWrap.classList.add('[--color:var(--color-danger)]');
                                        iconWrap.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="x-circle" class="lucide lucide-x-circle size-6 stroke-(--color) fill-(--color)/25"><circle cx="12" cy="12" r="10"></circle><path d="m15 9-6 6"></path><path d="m9 9 6 6"></path></svg>';
                                    } else {
                                        iconWrap.classList.remove('[--color:var(--color-danger)]');
                                        iconWrap.classList.add('[--color:var(--color-success)]');
                                        iconWrap.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="check-circle" class="lucide lucide-check-circle size-6 stroke-(--color) fill-(--color)/25"><path d="M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20"></path><path d="m9 12 2 2 4-4"></path></svg>';
                                    }
                                }
                            }
                            // attach payload so hideToast can dispatch it when closed
                            try { node.__menuToastPayload = options || null; } catch(_) {}
                            return node;
                        }

                        // Show helper used by click/event (stack argument)
                        function showToastNode(targetStack, node, autoHideMs) {
                            node.style.height = '0px';
                            targetStack.appendChild(node);
                            requestAnimationFrame(function(){
                                try { node.style.height = node.scrollHeight + 'px'; } catch (_) {}
                                node.classList.remove('opacity-0','translate-y-2');
                            });
                            var openEnd = function(e){ if (e && e.target !== node) return; node.removeEventListener('transitionend', openEnd); node.style.height = 'auto'; };
                            node.addEventListener('transitionend', openEnd);
                            function hideToast() {
                                try { node.style.height = node.scrollHeight + 'px'; void node.offsetHeight; node.style.height = '0px'; } catch (_) {}
                                node.classList.add('opacity-0','-translate-y-2');
                                var removed = false; var removeFn = function(){ if (removed) return; removed = true; node.removeEventListener('transitionend', removeFn); node.remove(); };
                                node.addEventListener('transitionend', removeFn); setTimeout(removeFn, 500);
                            }
                            var closer = node.querySelector('.close-btn'); if (closer) closer.addEventListener('click', hideToast);
                            var t = Number(autoHideMs || timeout); if (t > 0) setTimeout(hideToast, t);
                        }

                        // Register this stack/template in a global registry (prune stale, keep latest)
                        window.__menuToastStacks = window.__menuToastStacks || [];
                        var currentId = stack && stack.id;
                        window.__menuToastStacks = window.__menuToastStacks.filter(function(ctx){
                            return ctx && ctx.stack && ctx.stack.isConnected && ctx.tmpl && ctx.tmpl.isConnected && (!currentId || ctx.stack.id !== currentId);
                        });
                        window.__menuToastStacks.push({ stack: stack, tmpl: tmpl, timeout: timeout });

                        // Resolver that always picks a connected context; falls back to scanning DOM
                        function resolveCtx(){
                            try {
                                var reg = window.__menuToastStacks || [];
                                for (var i = reg.length - 1; i >= 0; i--) {
                                    var c = reg[i];
                                    if (c && c.stack && c.stack.isConnected && c.tmpl && c.tmpl.isConnected) return c;
                                }
                                var stacks = document.querySelectorAll('[data-menu-toast-stack][data-menu-toast-uid]');
                                for (var j = stacks.length - 1; j >= 0; j--) {
                                    var s = stacks[j];
                                    if (!s || !s.isConnected) continue;
                                    var uid = s.getAttribute('data-menu-toast-uid');
                                    var t = document.querySelector('template[data-menu-toast-template][data-menu-toast-uid="' + uid + '"]');
                                    if (t && t.isConnected) {
                                        var to = Number(s.getAttribute('data-menu-toast-timeout')) || timeout;
                                        return { stack: s, tmpl: t, timeout: to };
                                    }
                                }
                            } catch(_) {}
                            return null;
                        }

                        // Global programmatic API (single instance)
                        if (!window.showMenuToast) {
                            window.showMenuToast = function(options){
                                try {
                                    var ctx = resolveCtx();
                                    if (!ctx) return;
                                    var node = buildToastNode(ctx.tmpl, options || {});
                                    showToastNode(ctx.stack, node, (options && options.autoHideMs) || ctx.timeout);
                                } catch(_) {}
                            };
                        }

                        // Livewire/browser event support (add once)
                        if (!window.__menuToastShowListenerAdded) {
                            window.__menuToastShowListenerAdded = true;
                            // Simple de-dup guard: ignore identical payloads within a short window
                            window.__menuToastDedup = window.__menuToastDedup || { lastKey: null, lastTs: 0 };
                            function shouldHandleToast(payload) {
                                try {
                                    var p = payload || {};
                                    var key = String(p.title || '') + '|' + String(p.message || '') + '|' + String(p.type || '');
                                    var now = Date.now();
                                    if (window.__menuToastDedup.lastKey === key && (now - window.__menuToastDedup.lastTs) < 500) {
                                        return false;
                                    }
                                    window.__menuToastDedup.lastKey = key;
                                    window.__menuToastDedup.lastTs = now;
                                    return true;
                                } catch(_) { return true; }
                            }
                            // Browser CustomEvent (bubble)
                            window.addEventListener('show-toast', function(e){
                                try {
                                    var detail = e && e.detail ? e.detail : null;
                                    var payload = Array.isArray(detail) ? detail[0] : detail;
                                    if (shouldHandleToast(payload)) {
                                        setTimeout(function(){ window.showMenuToast(payload || {}); }, 25);
                                    }
                                } catch(_) {}
                            });
                            // Document capture listener to catch element-targeted events that bubble
                            document.addEventListener('show-toast', function(e){
                                try {
                                    var detail = e && e.detail ? e.detail : null;
                                    var payload = Array.isArray(detail) ? detail[0] : detail;
                                    if (shouldHandleToast(payload)) {
                                        setTimeout(function(){ window.showMenuToast(payload || {}); }, 25);
                                    }
                                } catch(_) {}
                            }, true);
                            // Livewire JS event bus fallback
                            try {
                                if (window.Livewire && typeof window.Livewire.on === 'function' && !window.__menuToastLWListenerAdded) {
                                    window.__menuToastLWListenerAdded = true;
                                    window.Livewire.on('show-toast', function(payload){
                                        try {
                                            var p = Array.isArray(payload) ? payload[0] : payload;
                                            if (shouldHandleToast(p)) {
                                                setTimeout(function(){ window.showMenuToast(p || {}); }, 25);
                                            }
                                        } catch(_) {}
                                    });
                                }
                            } catch(_) {}
                        }

                        // Helper that shows a toast and invokes a callback when it's closed
                        if (!window.showMenuToastThen) {
                            window.showMenuToastThen = function(options, cb){
                                try {
                                    window.showMenuToast(options || {});
                                    if (typeof cb === 'function') {
                                        var handler = function(e){
                                            try { cb(e && e.detail ? e.detail : null); } catch(_) {}
                                            document.removeEventListener('menu-toast-closed', handler);
                                        };
                                        document.addEventListener('menu-toast-closed', handler);
                                    }
                                } catch(_) {}
                            };
                        }

                        // Click trigger with data attributes overrides
                        if (trigger) {
                            trigger.addEventListener('click', function (e) {
                                e.preventDefault();
                                var opts = {
                                    title: trigger.getAttribute('data-toast-title') || undefined,
                                    message: trigger.getAttribute('data-toast-message') || undefined,
                                    type: trigger.getAttribute('data-toast-type') || undefined
                                };
                                var node = buildToastNode(tmpl, opts);
                                var perClickTimeout = trigger.getAttribute('data-toast-timeout');
                                showToastNode(stack, node, perClickTimeout ? Number(perClickTimeout) : timeout);
                            });
                        }
                    }
                } catch(_) {}
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setup);
            } else { setup(); }
            window.addEventListener('livewire:load', setup);
            window.addEventListener('livewire:navigated', setup);
        })();
    </script>
    @endpush
</div>