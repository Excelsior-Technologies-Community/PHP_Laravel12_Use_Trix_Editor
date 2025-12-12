(function() {
    // HOST variable holds the URL where the Trix editor will upload images
    var HOST = fileUploadURL;

    // Listen for the 'trix-attachment-add' event when a file is attached in Trix
    addEventListener("trix-attachment-add", function(event) {
        // Check if the attachment has a file
        if (event.attachment.file) {
            // Upload the file attachment
            uploadFileAttachment(event.attachment);
        }
    });

    // Function to handle uploading a Trix attachment
    function uploadFileAttachment(attachment) {
        // Call the uploadFile function, passing callbacks for progress and success
        uploadFile(
            attachment.file,
            setProgress,     // Callback to set upload progress
            setAttributes    // Callback to set attributes after successful upload
        );

        // Function to update upload progress on Trix editor
        function setProgress(progress) {
            attachment.setUploadProgress(progress); // Show progress bar
        }

        // Function to set attachment attributes (like URL) after successful upload
        function setAttributes(attributes) {
            attachment.setAttributes(attributes); // Update the Trix editor with uploaded file URL
        }
    }

    // Function to upload a file via XMLHttpRequest
    function uploadFile(file, progressCallback, successCallback) {
        var formData = createFormData(file); // Create form data with the file
        var xhr = new XMLHttpRequest();       // Create a new AJAX request

        xhr.open("POST", HOST, true); // POST request to the upload URL
        xhr.setRequestHeader('X-CSRF-TOKEN', getMeta('csrf-token')); // Set CSRF token header

        // Track upload progress
        xhr.upload.addEventListener("progress", function(event) {
            var progress = event.loaded / event.total * 100; // Calculate progress %
            progressCallback(progress);                     // Call the progress callback
        });

        // When upload is complete
        xhr.addEventListener("load", function(event) {
            var attributes = {
                url: xhr.responseText,                        // URL of uploaded image
                href: xhr.responseText + "?content-disposition=attachment" // Optional download link
            };
            successCallback(attributes);                     // Call success callback with image URL
        });

        xhr.send(formData); // Send the file data
    }

    // Function to create FormData object for the file
    function createFormData(file) {
        var data = new FormData();
        data.append("Content-Type", file.type); // Include the file type
        data.append("file", file);              // Append the file itself
        return data;
    }

    // Function to get the content of a meta tag (used for CSRF token)
    function getMeta(metaName) {
        const metas = document.getElementsByTagName('meta');
        for (let i = 0; i < metas.length; i++) {
            if (metas[i].getAttribute('name') === metaName) {
                return metas[i].getAttribute('content'); // Return the meta content
            }
        }
        return ''; // Return empty string if meta not found
    }
})();
