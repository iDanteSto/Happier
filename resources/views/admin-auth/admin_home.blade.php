@extends('layouts.app')

@section('content')

                   
<div class="container">
  <div class="row" id="users" style="margin-top: 60px">
    <div class="col-md-6">
        <table id="table_id" class="display">
          <thead>
                <tr>
                    <th>Column 1</th>
                    <th>Column 2</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Row 1 Data 1</td>
                    <td>Row 1 Data 2</td>
                </tr>
                <tr>
                    <td>Row 2 Data 1</td>
                    <td>Row 2 Data 2</td>
                </tr>
            </tbody>
        </table>  
    </div>
    <!--
      <div class="panel-group">
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading" align="center"><strong>Avatars</strong></div>
                <div class="panel-body">
                  <div class="col-md-6">
                    <a href="{!!url('avatar_categories')!!}">
                          <div class="ibox">
                              <div class="ibox-content product-box" style ="background-color: gold">
                                  <div class="product-imitation">
                                        <img alt="logo" src="{{url('/img/archive_img.png')}}" width="100" height="100">
                                  </div>
                                  <div class="product-desc">
                                      <p class="product-name" align="center"> Categorias</p>
                                  </div>
                              </div>
                          </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                    <a href="{!!url('avatars')!!}">
                          <div class="ibox">
                              <div class="ibox-content product-box" style ="background-color: gold">
                                  <div class="product-imitation">
                                        <img alt="logo" src="{{url('/img/archive_img.png')}}" width="100" height="100">
                                  </div>
                                  <div class="product-desc">
                                      <p class="product-name" align="center"> Avatars</p>
                                  </div>
                              </div>
                          </div>
                        </a>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading" align="center"><strong>Recomendaciones</strong></div>
                <div class="panel-body">
                  <div class="col-md-6">
                    <a href="{!!url('recommendation_categories')!!}">
                          <div class="ibox">
                              <div class="ibox-content product-box" style ="background-color: gold">
                                  <div class="product-imitation">
                                        <img alt="logo" src="{{url('/img/archive_img.png')}}" width="100" height="100">
                                  </div>
                                  <div class="product-desc">
                                      <p class="product-name" align="center"> Temas</p>
                                  </div>
                              </div>
                          </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                    <a href="{!!url('recommendations')!!}">
                          <div class="ibox">
                              <div class="ibox-content product-box" style ="background-color: gold">
                                  <div class="product-imitation">
                                        <img alt="logo" src="{{url('/img/archive_img.png')}}" width="100" height="100">
                                  </div>
                                  <div class="product-desc">
                                      <p class="product-name" align="center"> Recom</p>
                                  </div>
                              </div>
                          </div>
                        </a>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading" align="center"><strong>Noticias</strong></div>
                <div class="panel-body">
                  <div class="col-md-12">
                    <a href="{!!url('news')!!}">
                          <div class="ibox">
                              <div class="ibox-content product-box" style ="background-color: gold">
                                  <div class="product-imitation">
                                        <img alt="logo" src="{{url('/img/archive_img.png')}}" width="100" height="100">
                                  </div>
                                  <div class="product-desc">
                                      <p class="product-name" align="center"> Noticia</p>
                                  </div>
                              </div>
                          </div>
                        </a>
                    </div>
                </div>
              </div>
            </div>
      </div>
    -->
  </div>
</div>





                    
@endsection
