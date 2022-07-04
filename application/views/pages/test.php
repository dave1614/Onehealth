<script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      console.log('gol');
      $("#test").click(function(evt){
        swal({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            swal(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )
          }
        })
      // swal("Alert");
      });
      <?php
        if($no_sub_admin == true){
      ?>
        // swal({
        //   title: 'Are you sure?',
        //   text: "You won't be able to revert this!",
        //   type: 'warning',
        //   showCancelButton: true,
        //   confirmButtonColor: '#3085d6',
        //   cancelButtonColor: '#d33',
        //   confirmButtonText: 'Yes, delete it!'
        // }).then((result) => {
        //   if (result.value) {
        //     swal(
        //       'Deleted!',
        //       'Your file has been deleted.',
        //       'success'
        //     )
        //   }
        // })
      <?php
        }
      ?>

    });
  </script>