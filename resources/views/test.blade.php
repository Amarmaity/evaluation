<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="container">
                    <div class="form-container">
                        <div class="form-header">
                            <h2>Insert Data</h2>
                        </div>

                        <form action="{{route('insert-data')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                        <div class="form-section">
                            <h5>Account Information</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="user_type" class="form-label">User Type</label>
                                    <select class="form-control" id="user_type" name="user_type" required>
                                    <option value="" selected>Select User Type</option>
                                    <option value="Super User" >Super User</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit">Save User</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
</body>