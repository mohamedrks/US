
<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: rikazdev-->
<!-- * Date: 4/10/15-->
<!-- * Time: 9:38 AM-->
<!-- */-->



<div class="about-section">
   <div class="text-content">
     <div class="span7 offset1">

          <div class="alert-box success">

          </div>

        <div class="secure">Upload form</div>
        {!! Form::open(array('url'=>'apply/upload','method'=>'POST', 'files'=>true)) !!}
         <div class="control-group">
          <div class="controls">
          {!! Form::file('image') !!}
	  <p class="errors">{!!$errors->first('image')!!}</p>

	<p class="errors">{!! Session::get('error') !!}</p>

        </div>
        </div>
        <div id="success"> </div>
      {!! Form::submit('Submit', array('class'=>'send-btn')) !!}
      {!! Form::close() !!}
      </div>
   </div>
</div>