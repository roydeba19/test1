@extends('../site_layout')
<!-- <link href="{{url('public')}}/auto-suggest/style.css" rel='stylesheet' type='text/css'> -->
  <link href="{{url('public')}}/auto-suggest/jquery.mentionsInput.css" rel='stylesheet' type='text/css'>
@section('section_content')
<div class="intro-container">
      <div id="introCarousel" class="carousel slide carousel-fade" data-ride="carousel">

        <ol class="carousel-indicators"></ol>

        <div class="carousel-inner" role="listbox">

          <div class="carousel-item inner-carouselitm active">
            <div class="carousel-background"><img src="{{url('public')}}/img/intro-carousel/1.jpg" alt=""></div>
            <div class="carousel-container">
              <div class="carousel-content">
                <h1>Create</h1>
                <h2>Post</h2>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('content')
 <!--==========================
      Events Section
    ============================-->
    <section id="events" class="wow fadeInUp">
    <div class="container">
    <div class="row">
       <div class="col-md-12">
            <div class="crt-heading">Create Post</div>
        </div>
       </div>    
		<div class="events-filbx">
        <form action="" id="createPostForm" class="xazak_form">
        <div class="row">
        <div class="form-group">
        <div class="col-md-1">
        <img src="{{url('')}}/public/img/post-pic.png" alt="" />
        </div>
        <div class="col-md-11"><textarea cols="4" class="mention" rows="6" id="description" placeholder="Write your post..."></textarea>
        <div class="cre-pstlwbx">
        <img src="{{url('')}}/public/img/recent-pic.png" alt="" />
        <div class="crt-psticon">
       <i class="ion-image" onclick="$('#post_image').trigger('click');"></i> 
        <input type="file" id="post_image" name="image">
        </div>
        </div>
        </div>
        </div>
        </div>
        
        
        <div class="crt-heading">Select Circle</div>
        @foreach ($circle_list as $cl)
        <div class="post-circle wow fadeInUp" data-wow-delay="0.1s" style="cursor:pointer;">
          @if (!empty($cl->image))
                <img src="{{url('')}}/public/upload/circle_image/{{$cl->image}}" alt="" class="imageo">
          @endif      
        <div class="middle circle_select" data-id="{{$cl->id}}">
            <div class="text"><img src="{{url('')}}/public/img/right.png" alt=""></div>
        </div>
              <h2 class="pst-crtitle">{{$cl->name}}</h2>
            </div>
            @endforeach  
            <!-- hidden fields -->
            <input type="hidden" id="circle_temp_ids">

        <div class="col-md-12 text-center">
        <div class="form-group">
        <button type="submit" class="btn btn-danger"><img src="{{url('')}}/public/img/pst-in.png" alt="" /> Post</button>
        </div>
        </div>
 		</div>
        </form>
		</div>
      </div>
    </section>
@endsection

@section('extra_js')
<script>
  $(".circle_select").click(function() {
        $(this).toggleClass("active1");
        var is_selected = $(this).hasClass('active1');
        if (is_selected) {          
          var kid_ids = $('#circle_temp_ids').val() + $(this).data('id') + ',';                     
        } else {
          var kid_ids = $('#circle_temp_ids').val().replace($(this).data('id') + ',','');
        }    
        $('#circle_temp_ids').val(kid_ids);
  });
 $(function () { 

  $('#createPostForm').on('submit', function (e) {
        if ($("#description").val() == '') {
            alert('Please enter your message');
            return false;
        }

        var user_ids = '';
        var bus_ids = '';
        $('textarea.mention').mentionsInput('getMentions', function(data) {
   // console.log(data);
    data.forEach(function(element) {
      console.log(element);
      if (element.type == 'user') {
        user_ids += element.id + ',';
        console.log(user_ids);
      } else {
        bus_ids += element.id + ',';
      }
    });
   // alert(JSON.stringify(data));
      // user_ids = user_ids.slice(0,1);
      // bus_ids = bus_ids.slice(0,1);
       console.log(user_ids.replace(/.$/,""));
      // console.log(bus_ids);
  });
        
        var formData = new FormData();
        formData.append('user_id',{{Session::get('user_id')}});
        formData.append('description', $("#description").val());
        formData.append('image', $('input[type=file]')[0].files[0]);
        formData.append('shared_with', $('#circle_temp_ids').val().replace(/.$/,""));
        formData.append('tagged_user_id',user_ids);
        formData.append('tagged_business_id', '');

          e.preventDefault();
         //  console.log(formData);
        //   return false;
          $.ajax({
          type: 'post',
          url: '{{url('')}}/api/post-create',
          data: formData,          
          processData: false,
          contentType: false,
          success: function (response) {
            console.log(response);
            if (response.result == true) {
              alert("" + response.message + "");
              window.location = "{{url('posts')}}";            
              
            } else {
              alert("" + response.message + "");
            }
            
          },
          error: function () {
            alert('Some Error Occured!');
          }
          });
      });      
 });
</script>
<script src='//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js' type='text/javascript'></script>

<script src="{{url('public')}}/auto-suggest/lib/jquery.events.input.js" type='text/javascript'></script>
<script src="{{url('public')}}/auto-suggest/lib/jquery.elastic.js" type='text/javascript'></script>
<script src="{{url('public')}}/auto-suggest/jquery.mentionsInput.js" type='text/javascript'></script>

  <script>
    $(function () {

$('textarea.mention').mentionsInput({
  onDataRequest:function (mode, query, callback) {
    var data = [
      { id:1, name:'Kenneth Auchenberg',  'type':'user' },
      { id:2, name:'Jon Froda',  'type':'user' },
      { id:3, name:'Anders Pollas', 'type':'business' }
    ];

    data = _.filter(data, function(item) { return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1 });

    callback.call(this, data);
  },
minChars:1,  
onCaret: true
});
});
</script>
@endsection
