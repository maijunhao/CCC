@extends('admin.layout.index');
    @section('css')

        <style type="text/css">
            #page_page ul,#page_page li{
                margin: 0;
                padding: 0;
                list-style-type: none;
            }
            #page_page a,#page_page span{
                position: relative;
                float: left;
                padding: 6px 12px;
                margin-left: -1px;
                line-height: 1.42857143;
                color: #337ab7;
                text-decoration: none;
                background-color: #fff;
                border: 1px solid #ddd;
            }
            #page_page .active span{
                color: #fff;
                background-color: #40c088;
            }

        </style>
    @endsection
@section('content')
    <form action="/admin/users" method="get">
        姓名：<input type="text" name="search_uname" value="{{ $params['search_uname'] or '' }}" >
        邮箱：<input type="text" name="search_email" value="{{ $params['search_email'] or '' }}">
        <input type="submit" value="搜索">
    </form>

    <div class="mws-panel grid_8">
        <div class="mws-panel-header">
            <span><i class="icon-table"></i> 用户列表</span>
        </div>
        <div class="mws-panel-body no-padding">
            <table class="mws-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>用户名</th>
                    <th>邮箱</th>
                    <th>手机号</th>
                    <th>头像</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($users as $k=>$v)
                    <tr>
                        <td>{{ $v->id }}</td>
                        <td>{{ $v->uname }}</td>
                        <td>{{ $v->email }}</td>
                        <td>{{ $v->phone }}</td>
                        <td>
                            <img style="border-radius: 5px;width: 50px " src="/uploads/{{ $v->userinfo->profile }}" alt="">
                        </td>
                        <td>{{ $v->created_at }}</td>
                        <td>
                            <a href="/admin/users/{{ $v->id }}/edit" class="btn btn-warning">修改</a>
                            <form action="/admin/users/{{ $v->id }}" method="post" style="display: inline-block">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <input type="submit" value="删除" class="btn btn-danger">
                            </form>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="page_page">
                  {{ $users->appends($params)->links() }}
            </div>
        </div>
    </div>
@endsection