@extends('admin.layout.index');

@section('content')
    @if ($errors->any())
        <div class="mws-form-message error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <div class="mws-panel-header">
            <span>用户修改</span>
        </div>
        <div class="mws-panel-body no-padding">
            <form class="mws-form" action="/admin/users/{{ $user->id }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('put') }}
                <div class="mws-form-inline">
                    <div class="mws-form-row">
                        <label class="mws-form-label">用户名</label>
                        <div class="mws-form-item">
                            <input type="text" disabled name="uname" class="small" value="{{ $user->uname }}">
                        </div>
                    </div>
                    <div class="mws-form-row">
                        <label class="mws-form-label">邮箱</label>
                        <div class="mws-form-item">
                            <input type="text" name="email" class="small" value="{{ $user->email }}">
                        </div>
                    </div>
                    <div class="mws-form-row">
                        <label class="mws-form-label">手机号</label>
                        <div class="mws-form-item">
                            <input type="text" name="phone" class="small" value="{{ $user->phone }}">
                        </div>
                    </div>
                    <img src="/uploads/{{ $user->userinfo->profile }}" alt="" style="width: 80px">
                    
                    <div class="mws-form-row">
                        <label class="mws-form-label">头像</label>
                        <div class="mws-form-item" style="width:440px">
                            <input type="file" name="profile" class="small">
                        </div>
                    </div>

                </div>
                <div class="mws-button-row">
                    <input type="submit" value="Submit" class="btn btn-danger">
                    <input type="reset" value="Reset" class="btn ">
                </div>
            </form>
        </div>
    </div>
@endsection