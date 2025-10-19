/**
 * Universal Data Initializer
 * Automatically reads data-* attributes and initializes window objects
 * No more inline scripts needed!
 */

document.addEventListener('DOMContentLoaded', function() {
    // Find all elements with data-window-var attribute
    const dataElements = document.querySelectorAll('[data-window-var]');
    
    dataElements.forEach(function(element) {
        const varName = element.dataset.windowVar;
        const dataObj = {};
        
        // Loop through all data attributes except data-window-var
        Object.keys(element.dataset).forEach(function(key) {
            if (key !== 'windowVar') {
                let value = element.dataset[key];
                
                // Convert camelCase to proper key name
                const propName = key;
                
                // Try to parse JSON values
                try {
                    // Special case for formJson - parse entire JSON object
                    if (propName === 'formJson') {
                        Object.assign(dataObj, JSON.parse(value));
                        return; // Skip adding formJson as a property
                    }
                    
                    // Special case for userKelurahanJson
                    if (propName === 'userKelurahanJson') {
                        dataObj['userKelurahan'] = JSON.parse(value);
                        return; // Skip adding as original key
                    }
                    
                    // Special case for user sub-object (userKelurahan, userKecamatan)
                    if (propName === 'userKelurahan' || propName === 'userKecamatan') {
                        if (!dataObj.user) {
                            dataObj.user = {};
                        }
                        // Remove "user" prefix and lowercase first letter
                        const subKey = propName.replace('user', '');
                        const finalKey = subKey.charAt(0).toLowerCase() + subKey.slice(1);
                        dataObj.user[finalKey] = value;
                        return; // Skip adding as top-level property
                    }
                    
                    // Check if value looks like JSON
                    if (value === 'null' || value === 'undefined') {
                        dataObj[propName] = null;
                    } else if (value === 'true' || value === 'false') {
                        dataObj[propName] = value === 'true';
                    } else if (!isNaN(value) && value.trim() !== '' && !value.includes('/')) {
                        dataObj[propName] = parseFloat(value);
                    } else if (value.startsWith('{') || value.startsWith('[')) {
                        dataObj[propName] = JSON.parse(value);
                    } else {
                        // Handle special cases like deleteBaseUrl (replace :id with empty string)
                        if (propName === 'deleteBaseUrl' && value.includes(':id')) {
                            dataObj[propName] = value.replace(':id', '');
                        } else {
                            dataObj[propName] = value;
                        }
                    }
                } catch (e) {
                    // If parsing fails, use raw value
                    dataObj[propName] = value;
                }
            }
        });
        
        // Initialize window variable
        window[varName] = dataObj;
        
        // Log for debugging (remove in production)
        // console.log('Initialized:', varName, dataObj);
    });
});
