/**
 * TypeUp Analytics Tracker
 * A lightweight JavaScript tracking library for capturing search and click events
 */

(function () {
    'use strict';

    // Configuration
    const CONFIG = {
        apiBaseUrl: window.location.origin + '/api/track',
        scriptUrl: window.location.origin + '/js/tracker.js',
        debug: false
    };

    // State management
    let siteKey = null;
    let currentSearchId = null;
    let isInitialized = false;

    /**
     * Log debug messages
     */
    function log(message, data = null) {
        if (CONFIG.debug && console && console.log) {
            console.log('[TypeUp Tracker]', message, data || '');
        }
    }

    /**
     * Send data to the tracking API
     */
    function sendToAPI(endpoint, data) {
        return fetch(CONFIG.apiBaseUrl + endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .catch(error => {
                log('API Error:', error);
                throw error;
            });
    }

    /**
     * Initialize the tracker with site key
     */
    function init(siteKeyParam) {
        if (isInitialized) {
            log('Tracker already initialized');
            return;
        }

        siteKey = siteKeyParam;
        isInitialized = true;

        log('Tracker initialized with site key:', siteKey);

        // Auto-attach click listeners to elements with data-content-id
        attachClickListeners();
    }

    /**
     * Initialize a new search session
     */
    function initSearch(metadata = {}) {
        if (!isInitialized) {
            log('Tracker not initialized');
            return Promise.reject(new Error('Tracker not initialized'));
        }

        log('Initializing search with metadata:', metadata);

        // This would typically be called from the server-side
        // For client-side usage, we'll need to implement a different approach
        return Promise.reject(new Error('Search initialization must be done server-side with proper authentication'));
    }

    /**
     * Track a click event
     */
    function trackClick(element, additionalMetadata = {}) {
        if (!isInitialized) {
            log('Tracker not initialized');
            return Promise.reject(new Error('Tracker not initialized'));
        }

        if (!currentSearchId) {
            log('No active search session');
            return Promise.reject(new Error('No active search session'));
        }

        const contentId = element.getAttribute('data-content-id');
        if (!contentId) {
            log('Element missing data-content-id attribute');
            return Promise.reject(new Error('Element missing data-content-id attribute'));
        }

        // Calculate position (index among siblings with data-content-id)
        const position = calculatePosition(element);

        const clickData = {
            site_key: siteKey,
            search_id: currentSearchId,
            content_id: contentId,
            position: position,
            metadata: {
                element_type: element.tagName.toLowerCase(),
                click_time: new Date().toISOString(),
                ...additionalMetadata
            }
        };

        log('Tracking click:', clickData);

        return sendToAPI('/click', clickData)
            .then(response => {
                log('Click tracked successfully:', response);
                return response;
            });
    }

    /**
     * Calculate the position of an element among its siblings
     */
    function calculatePosition(element) {
        const parent = element.parentElement;
        if (!parent) return 1;

        const siblings = Array.from(parent.children).filter(child =>
            child.hasAttribute('data-content-id')
        );

        return siblings.indexOf(element) + 1;
    }

    /**
     * Attach click listeners to elements with data-content-id
     */
    function attachClickListeners() {
        // Use event delegation for better performance
        document.addEventListener('click', function (event) {
            const element = event.target.closest('[data-content-id]');
            if (!element) return;

            // Only track if we have an active search session
            if (currentSearchId) {
                trackClick(element).catch(error => {
                    log('Failed to track click:', error);
                });
            }
        });

        log('Click listeners attached');
    }

    /**
     * Set the current search ID (called by client after server-side search init)
     */
    function setSearchId(searchId) {
        currentSearchId = searchId;
        log('Search ID set:', searchId);
    }

    /**
     * Clear the current search session
     */
    function clearSearch() {
        currentSearchId = null;
        log('Search session cleared');
    }

    // Auto-initialize from script tag attributes
    function autoInit() {
        const scriptTag = document.querySelector('script[src*="tracker.js"]');
        if (scriptTag) {
            const siteKeyFromScript = scriptTag.getAttribute('data-site-key');
            if (siteKeyFromScript) {
                init(siteKeyFromScript);
            }
        }
    }

    // Public API
    const TypeUpTracker = {
        init: init,
        initSearch: initSearch,
        trackClick: trackClick,
        setSearchId: setSearchId,
        clearSearch: clearSearch,
        config: CONFIG
    };

    // Make it globally available
    window.TypeUpTracker = TypeUpTracker;

    // Auto-initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', autoInit);
    } else {
        autoInit();
    }

    log('TypeUp Tracker loaded');

})();
