<script type="text/javascript">
function getImageURL(image){
  return validURL(image)?image:'{{ url('storage') }}/'+image;
}

function modalImage(imagePath,id){
  var modal =  `
  <!-- Modal -->
<div class="modal fade" id="avatar_image{{ $resourceName }}`+id+`" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <i data-bs-dismiss="modal"  style="cursor: pointer;" class="fa fa-times"></i>
      </div>
      <div class="modal-body">
           <img src="`+imagePath+`" alt="" style="width:100%;height:100%;">
      </div>
    </div>
  </div>
</div>

<!--Modal: Media Image-->
  `;
 $('.imagesModal').append(modal);

}
</script>

<span class="imagesModal"></span>
