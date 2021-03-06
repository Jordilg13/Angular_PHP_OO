/**
 * initialize the dropzone and it's configuration
 * 
 * 
 * @example <div class="dropzone" dropzone="dropzoneConfig"></div>
 */
project.directive('dropzone', function () {
    return function (scope, element, attrs) {
        var config = scope[attrs.dropzone];

        dropzone = new Dropzone(element[0], config.options);

        angular.forEach(config.eventHandlers, function (handler, event) {
            
            dropzone.on(event, handler);
        })
    }
})
