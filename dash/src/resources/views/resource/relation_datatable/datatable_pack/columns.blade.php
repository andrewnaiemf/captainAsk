@if($multiSelectRecord)
      { "data":null,
        orderable:false,
        searchable:false,
        targets:0,
        render: function(data,type,full,meta){
          return `

                    <center>
                    <input class="form-check-input border selectID{{ $resourceName }}" type="checkbox" id="selectID{{ $resourceName }}" value="`+data.id+`">
                    </center>
          `;
        }
      },
@endif

    @foreach($fields as $field)
    @if($field['show_rules']['showInIndex'])
      @if($field['type'] == 'image')
       {
        "data":null,
        orderable:false,
        searchable:false,
        render: function(data,type,full,meta){
          var imageCol  = data.{{ $field['attribute'] }};
          if(Object.keys(imageCol.trim()).length > 0){
            var imageLink = getImageURL(imageCol);
            modalImage(imageLink,data.id);
            return `
            <img class="img-fluid rounded-circle img-thumbnail"
             src="`+imageLink+`" alt="image"
             style="cursor: pointer;width:48px;height:48px"
             data-bs-toggle="modal"
             data-bs-target="#avatar_image{{ $resourceName }}`+data.id+`">
            `;
          }else{
            return '-';
          }
        }
      },
      @elseif($field['type'] == 'video')
        {
        "data":null,
        orderable:false,
        searchable:false,
        render: function(data,type,full,meta){
          if(data.{{ $field['attribute'] }} != null){
            var videoLink = getVideoURL(data.{{ $field['attribute'] }});
            modalVideo(videoLink,data.id);
            return `
            <a href="#void"
            data-bs-toggle="modal"
             data-bs-target="#video_box_{{ $resourceName }}`+data.id+`"
            ><i class="fa fa-video-camera"></i></a>
            `;
          }else{
            return '-';
          }
        }
      },
      @elseif($field['type'] == 'audio')
        {
        "data":null,
        orderable:false,
        searchable:false,
        render: function(data,type,full,meta){
          if(data.{{ $field['attribute'] }} != null){
            var audioLink = getaudioURL(data.{{ $field['attribute'] }});
            modalAudio(audioLink,data.id);
            return `
            <a href="#void"
            data-bs-toggle="modal"
             data-bs-target="#audio_box_{{ $resourceName }}`+data.id+`"
            ><i class="fa-solid fa-file-audio"></i></a>
            `;
          }else{
            return '-';
          }
        }
      },
      @elseif($field['type'] == 'file')
        {
        "data":null,
        orderable:false,
        searchable:false,
        render: function(data,type,full,meta){
          if(data.{{ $field['attribute'] }} != null){
            var audioLink = getaudioURL(data.{{ $field['attribute'] }});
             return `<a href="`+audioLink+`" target="_blank">
                      <i class="fa fa-download"></i>
                    </a>`;
          }else{
            return '-';
          }
        }
      },
       @elseif($field['type'] == 'checkbox')
        {
        "data":null,
        orderable: {{!$field['orderable']?'false':'true'}} ,
        searchable:{{!$field['searchable']?'false':'true' }},
        render: function(data,type,full,meta){
           var field = data.{{ $field['attribute'] }};
           @if(isset($field['trueVal']) && isset($field['falseVal']))
           if('{{ $field['trueVal'] }}' == field){
            return `<i class="fa fa-circle" style="color:#090;width:10px;height:10px" aria-hidden="true"></i>`;
           }else if('{{ $field['falseVal'] }}' == field){
            return `<i class="fa fa-circle" style="color:#c33;width:10px;height:10px" aria-hidden="true"></i>`;
           }else{
            return '-';
           }
           @else
            return '-';
           @endif
        }
      },
      @elseif($field['type'] == 'select')
            {
        "data":null,
        orderable: {{!$field['orderable']?'false':'true'}} ,
        searchable:{{!$field['searchable']?'false':'true' }},
        render: function(data,type,full,meta){
           var options = {!! json_encode($field['options'],JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!};
           var field = data.{{ $field['attribute'] }};
           if(options !== null){
            return options[field]??field;
           }else {
            return field;
           }
        }
      },
      @elseif(in_array($field['type'], $relationTypes))
      {
//Relationship statement Start
@if($field['type'] == 'morphOne')
 @include('dash::resource.datatable_pack.relationColumns.morphOne')
@elseif($field['type'] == 'morphTo')
 @include('dash::resource.datatable_pack.relationColumns.morphTo')
@elseif($field['type'] == 'morphMany')
 @include('dash::resource.datatable_pack.relationColumns.morphMany')
@elseif($field['type'] == 'morphToMany')
 @include('dash::resource.datatable_pack.relationColumns.morphToMany')
@elseif($field['type'] == 'hasMany')
 @include('dash::resource.datatable_pack.relationColumns.hasMany')
@elseif($field['type'] == 'belongsToMany')
 @include('dash::resource.datatable_pack.relationColumns.belongsToMany')
@elseif($field['type'] == 'hasManyThrough')
 @include('dash::resource.datatable_pack.relationColumns.hasManyThrough')
@elseif($field['type'] == 'hasOneThrough')
 @include('dash::resource.datatable_pack.relationColumns.hasOneThrough')
@elseif($field['type'] == 'hasOne')
 @include('dash::resource.datatable_pack.relationColumns.hasOne')
@elseif($field['type'] == 'belongsTo')
 @include('dash::resource.datatable_pack.relationColumns.belongsTo')
@endif
//Relationship statement End
      },
      @else
      {
           "data": null,
           orderable: {{!$field['orderable']?'false':'true'}},
           searchable:{{!$field['searchable']?'false':'true' }},
           render: function(data,type,full,meta){
              {{-- Render Start --}}
              if(data!=='null'){
                  if(data?.deleted_at != null){
                    return  ' <i class="fa fa-recycle fa-1x" style="color:#c33"></i> ' + data.{{ !empty($field['attribute'])?$field['attribute']:'id' }};
                  }else{
                    return   data.{{ !empty($field['attribute'])?$field['attribute']:'id' }};
                  }
               }else{
                  return  '-';
               }

             {{-- Render End --}}
           }
      },
      @endif
      {{-- End if Column Type --}}
      @endif
      {{-- End if rules show pages to index --}}
      @endforeach
      {

    orderable: false,
    "data": null,
    render:function (data, type, full, meta){
      var buttons = `
      @if($pagesRules['edit'])
      <a href='{{ url($DASHBOARD_PATH.'/resource/'.$resource['resourceName']) }}/edit/`+data.id+`'><i class='fa fa-edit'></i></a>
      @endif
      @if($pagesRules['show'])
      <a href='{{ url($DASHBOARD_PATH.'/resource/'.$resource['resourceName']) }}/`+data.id+`'><i class='fa fa-eye'></i></a>
      @endif
      @if($pagesRules['destroy'])
      <a href="#" action='{{ url($DASHBOARD_PATH.'/resource/'.$resource['resourceName']) }}/`+data.id+`' rowid="`+data.id+`" class="deleteRow{{ $resourceName }}"><i class='fa fa-trash'></i></a>
      @endif
      `;
       @if($pagesRules['restore'])
      if(data?.deleted_at != null){
      buttons +=`<a href="#void" action='{{ url($DASHBOARD_PATH.'/resource/'.$resource['resourceName']) }}/restore/`+data.id+`' rowid="`+data.id+`" class="restoreRow{{ $resourceName }}"><i class="fa fa-recycle fa-1x" style="color:#c33"></i><a>`;
      }
      @endif
      return buttons;
    },
    "defaultContent": "",
    //"targets": -1

  },
