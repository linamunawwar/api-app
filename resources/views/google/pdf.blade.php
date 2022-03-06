<style type="text/css">
            body{
    background:#eee;
    }                
    .ibox-content {
        background-color: #FFFFFF;
        color: inherit;
        padding: 15px 20px 20px 20px;
        border-color: #E7EAEC;
        border-image: none;
        border-style: solid solid none;
        border-width: 1px 0px;
    }

    .search-form {
        margin-top: 10px;
    }

    .search-result h3 {
        margin-bottom: 0;
        color: #1E0FBE;
    }

    .search-result .search-link {
        color: #006621;
    }

    .search-result p {
        font-size: 12px;
        margin-top: 5px;
    }

    .hr-line-dashed {
        border-top: 1px dashed #E7EAEC;
        color: #ffffff;
        background-color: #ffffff;
        height: 1px;
        margin: 20px 0;
    }

    h2 {
        font-size: 24px;
        font-weight: 100;
    }           
</style>
<h1>Google Search</h1>
 <div class="result">
    <div class="container bootstrap snippets bootdey">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    {{$queries['request'][0]['title']}}
                    <div class="ibox-content">

                        @foreach($items as $item)
                            <div class="hr-line-dashed"></div>
                            <div class="search-result">
                                <h3><a href="#">{{$item['title']}}</a></h3>
                                <a href="{{$item['link']}}" class="search-link">{{$item['link']}}</a>
                                <p>{{$item['snippet']}}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>