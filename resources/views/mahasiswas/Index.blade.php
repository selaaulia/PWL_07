@extends("layout")
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left mt-2">
                <h2>JURUSAN TEKNOLOGI INFORMASI-POLITEKNIK NEGERI MALANG</h2>
            </div>
            <div class="float-left my-4">
                <form action="/mahasiswas/cari/" method="GET">
                    <div class="input-group">
                        <input type="text" name="keyword" class="form-control" placeholder="Search users...">
                        <button type="submit" class="btn btn-primary">
                            Search
                        </button>
                    </div>
                </form>
            </div>
            <div class="float-right my-2">
                <a class="btn btn-success" href="{{ route('mahasiswas.create') }}"> Input Mahasiswa</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nim</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Tanggal Lahir</th>
                <th>No_Handphone</th>
                <th>Email</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($paginate as $Mahasiswa)
                <tr>
                    <td>{{ $Mahasiswa->Nim }}</td>
                    <td>{{ $Mahasiswa->Nama }}</td>
                    <td>{{ $Mahasiswa->Kelas->nama_kelas }}</td>
                    <td>{{ $Mahasiswa->Jurusan }}</td>
                    <td>{{ $Mahasiswa->tanggalLahir }}</td>
                    <td>{{ $Mahasiswa->No_Handphone }}</td>
                    <td>{{ $Mahasiswa->email }}</td>
                    <td>
                        <form action="{{ route('mahasiswas.destroy', $Mahasiswa->Nim) }}" method="POST">

                            <a class="btn btn-info" href="{{ route('mahasiswas.show', $Mahasiswa->Nim) }}">Show</a>

                            <a class="btn btn-primary" href="{{ route('mahasiswas.edit', $Mahasiswa->Nim) }}">Edit</a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">Delete</button>

                            <a class="btn btn-warning" href="{{ route('mahasiswas.nilai', $Mahasiswa->Nim) }}">Nilai</a>
                        </form>
                    </td>
                    </tr>
                    
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex">
        {{ $paginate->links() }}
    </div>
@endsection