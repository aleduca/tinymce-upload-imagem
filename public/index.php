<!DOCTYPE html>
<html>

<head>
  <script src="https://cdn.tiny.cloud/1/q32girmkj106tn6grxvn99jm1z4xqsdla3dnfweexj68mf8p/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>

<body>
  <textarea>
  </textarea>
  <script>
    tinymce.init({
      selector: 'textarea',
      height: 1000,
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      tinycomments_mode: 'embedded',
      // images_upload_url: 'upload.php',
      images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', 'upload.php');

        xhr.upload.onprogress = (e) => {
          progress(e.loaded / e.total * 100);
        };

        xhr.onload = () => {
          if (xhr.status === 403) {
            reject({
              message: 'HTTP Error: ' + xhr.status,
              remove: true
            });
            return;
          }

          if (xhr.status < 200 || xhr.status >= 300) {
            console.log(xhr);
            reject('HTTP Error: ' + xhr.status + ' ' + xhr.statusText);
            return;
          }

          const json = JSON.parse(xhr.responseText);

          if (!json || typeof json.location != 'string') {
            reject('Invalid JSON: ' + xhr.responseText);
            return;
          }

          resolve(json.location);
        };

        xhr.onerror = () => {
          reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
        };

        const formData = new FormData();
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        xhr.send(formData);
      })
    });
  </script>
</body>

</html>