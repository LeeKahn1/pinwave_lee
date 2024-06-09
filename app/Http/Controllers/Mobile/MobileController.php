<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Comment;
use App\Models\Following;
use App\Models\Pin;
use App\Models\Report;
use App\Models\User;
use App\Notifications\PinLiked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function Carbon\get;
use function PHPUnit\Framework\throwException;

class MobileController extends Controller
{
    public function testing()
    {
        return response()->json('Successfully test');
    }

    // fungsi untuk register (POST)
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|lowercase|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                $errorMessage = $validator->errors()->all()[0];

                return response()->json($errorMessage, 422);
            }

            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('Bearer')->plainTextToken;

            $user->bearer_token = $token;
            $user->save();

            return response()->json(['user' => $user, 'token' => $token], 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk login (POST)
    public function login(Request $request)
    {
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password]))
        {
            $user = Auth::user();
            $user->tokens()->delete();

            $success['token'] =  $user->createToken('Bearer')->plainTextToken;
            $success['name'] =  $user->username;

            $user->bearer_token = $success['token'];
            $user->save();

            return response()->json(['tokens' => $success]);
        } else
        {
            return response()->json('Username atau Password Salah', 422);
        }
    }

    // fungsi untuk logout (PUT)
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user) {
                $user->tokens()->delete();

                $user->bearer_token = null;
                $user->save();

                return response()->json('Successfully logged out', 200);
            } else {
                return response()->json('User not authenticated', 401);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk memperbarui profil user (POST)
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user) {
                $validator = Validator::make($request->all(), [
                    'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
                    'email' => 'nullable|string|lowercase|email|max:255|unique:users,email,' . $user->id,
                ]);

                if ($validator->fails()) {
                    $errorMessage = $validator->errors()->all()[0];
                    return response()->json($errorMessage, 422);
                }

                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $filename = time() . '.' . $photo->extension();
                    $photoPath = $photo->storeAs('public/profile_photos', $filename); // Store in 'public/profile_photos' directory
                    $oldPath = $user->profile_photo_path;
                    $user->profile_photo_path = $photoPath; // Update the path in the database
                }

                // Update other profile fields
                $user->fill($request->except('photo'));

                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }

                $user->save();

                if (!empty($oldPath)) {
                    Storage::delete($oldPath);
                }

                return response()->json('Successfully update profle', 200);
            } else {
                return response()->json('User not authenticated', 401);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk memperbarui password user (PUT)
    public function updatePassword(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user) {
                // Validate request data
                $validator = Validator::make($request->all(), [
                    'oldPassword' => 'required|string|min:6',
                    'newPassword' => 'required|string|min:6|confirmed',
                ]);

                if ($validator->fails()) {
                    $errorMessage = $validator->errors()->all()[0];
                    return response()->json($errorMessage, 422);
                }

                // Check if old password matches
                if (!Hash::check($request->oldPassword, $user->password)) {
                    return response()->json('The old password is incorrect.', 422);
                }

                if ($request->oldPassword === $request->newPassword) {
                    return response()->json('The old password and new password couldn\'t be same.', 422);
                }

                // Update to new password
                $user->password = Hash::make($request->newPassword);
                $user->save();

                return response()->json('Password successfully changed', 200);
            } else {
                return response()->json('User not authenticated', 401);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk mendapatkan semua data pin di halaman dasbor atau home (GET)
    public function getAllPin(Request $request) {
        try {
            $currentUserId = null;

            if ($request->hasHeader('Authorization')) {
                $bearerToken = $request->header('Authorization');
                $bearerToken = str_replace('Bearer ', '', $bearerToken);

                if (!empty($bearerToken)) {
                    $user = User::where('bearer_token', $bearerToken)->first();

                    if ($user) {
                        $currentUserId = $user->id;
                    }
                }
            }

            $pins = Pin::leftJoin('likes', function ($join) use ($currentUserId) {
                $join->on('pins.id', '=', 'likes.pin_id')->where('likes.user_id', '=', $currentUserId);
            })
                ->select('pins.*', 'likes.id as like_id')
                ->inRandomOrder()
                ->with('user')
                ->get();

            $pins->transform(function ($pin) {
                $pin->liked = !is_null($pin->like_id);
                unset($pin->like_id);

                return $pin;
            });

            $maps = [];

            foreach ($pins as $pin) {
                $map = new \stdClass();
                $map->id = $pin->id;
                $map->title = $pin->title;
                $map->description = $pin->description;
                $map->imageUrl = $pin->image_path ? url(Storage::url('pins/' . $pin->image_path)) : null;
                $map->link = $pin->link;
                $map->tags = $pin->tags;
                $map->liked = !is_null($pin->like_id);

                $map->users = new \stdClass();
                $map->users->username = $pin->user->username;
                $map->users->email = $pin->user->email;
                $map->users->imageUrl = $pin->user->profile_photo_path ? url(Storage::url($pin->user->profile_photo_path)) : null;

                $maps[] = $map;
            }

            return response()->json(['pins' => $maps], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk mendapatkan detail pin (GET)
    public function getPinDetails($id, Request $request) {
        try {
            $pin = Pin::with('user')->findOrFail($id);

            $currentIdUser = $request->user()->id;
            $liked = $pin->likes()->where('user_id', $currentIdUser)->exists();

            $likesCount = $pin->likes()->count();

            $followingCount = Following::where('followings_id', $pin->user->id)->count();
            $followed = Following::where('user_id', $currentIdUser)->where('followings_id', $pin->user->id)->exists();

            $reported = Report::where('user_id', $currentIdUser)->where('pin_id', $pin->id)->exists();

            $map = new \stdClass();
            $map->id = $pin->id;
            $map->title = $pin->title;
            $map->description = $pin->description;
            $map->imageUrl = $pin->image_path ? url(Storage::url('pins/' . $pin->image_path)) : null;
            $map->link = $pin->link;
            $map->tags = $pin->tags;
            $map->liked = $liked;
            $map->owned = $pin->user->id === $currentIdUser;
            $map->likesCount = $likesCount;
            $map->reported = $reported;

            $map->users = new \stdClass();
            $map->users->id = $pin->user->id;
            $map->users->username = $pin->user->username;
            $map->users->email = $pin->user->email;
            $map->users->imageUrl = $pin->user->profile_photo_path ? url(Storage::url($pin->user->profile_photo_path)) : null;
            $map->users->followingCount = $followingCount;
            $map->users->followed = $followed;

            $comments = $pin->comments()->get();

            $commentsData = [];
            foreach ($comments as $comment) {
                $commentMap = new \stdClass();
                $commentMap->id = $comment->id;
                $commentMap->owned = $comment->user_id === $currentIdUser;
                $commentMap->content = $comment->content;
                $commentMap->username = $comment->user->username;
                $commentMap->imageUrl = $comment->user->profile_photo_path ? url(Storage::url($comment->user->profile_photo_path)) : null;
                $commentMap->createdAt = $comment->created_at;

                $commentsData[] = $commentMap;
            }

            $map->comments = new \stdClass();
            $map->comments = $commentsData;

            return response()->json(['pins' => $map], 200);
        } catch (\Exception $e) {
            return response()->json('Pin not found', 404);
        }
    }

    // fungsi untuk mencari data pin di halaman search (GET)
    public function getSearchPin(Request $request)
    {
        $currentUserId = null;

        if ($request->hasHeader('Authorization')) {
            $bearerToken = $request->header('Authorization');
            $bearerToken = str_replace('Bearer ', '', $bearerToken);

            if (!empty($bearerToken)) {
                $user = User::where('bearer_token', $bearerToken)->first();

                if ($user) {
                    $currentUserId = $user->id;
                }
            }
        }

        $query = Pin::query();

        if ($request->has('keyword')) {
            $searchTerm = $request->input('keyword');

            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('tags', 'LIKE', "%{$searchTerm}%");
            });
        }

        $pins = $query->leftJoin('likes', function ($join) use ($currentUserId) {
            $join->on('pins.id', '=', 'likes.pin_id')
                ->where('likes.user_id', '=', $currentUserId);
        })
            ->select('pins.*', 'likes.id as like_id')
            ->inRandomOrder()
            ->with('user')
            ->get();

        $pins->transform(function ($pin) {
            $pin->liked = !is_null($pin->like_id);
            unset($pin->like_id);

            if ($pin->image_path) {
                $pin->image_url = url(Storage::url('pins/' . $pin->image_path));
            } else {
                $pin->image_url = null;
            }

            return $pin;
        });

        $maps = [];

        foreach ($pins as $pin) {
            $map = new \stdClass();
            $map->id = $pin->id;
            $map->title = $pin->title;
            $map->description = $pin->description;
            $map->imageUrl = $pin->image_path ? url(Storage::url('pins/' . $pin->image_path)) : null;
            $map->link = $pin->link;
            $map->tags = $pin->tags;
            $map->liked = !is_null($pin->like_id);

            $map->users = new \stdClass();
            $map->users->username = $pin->user->username;
            $map->users->email = $pin->user->email;
            $map->users->imageUrl = $pin->user->profile_photo_path ? url(Storage::url($pin->user->profile_photo_path)) : null;

            $maps[] = $map;
        }

        return response()->json(['pins' => $maps], 200);
    }

    // fungsi untuk membuat pin baru (POST)
    public function createPin(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'albumId' => 'nullable|exists:albums,id', // validasi untuk album_id
            ]);

            if ($validator->fails()) {
                $errorMessage = $validator->errors()->all()[0];
                return response()->json($errorMessage, 422);
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/pins', $imageName);

            $pin = new Pin;
            $pin->fill($request->all());
            $pin->user_id = $request->user()->id;
            $pin->image_path = $imageName;
            $pin->save();

            if ($request->has('albumId')) {
                $album = Album::find($request->albumId);
                if ($album && !$album->pins->contains($pin)) {
                    $album->pins()->attach($pin);
                }
            }

            return response()->json('Pin created successfully', 201);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk membuat komentar pada detail pin (POST)
    public function postCommentPin(Request $request) {
        try {
            $request->validate([
                'pinId' => 'required',
                'message' => 'required',
            ]);

            $comment = new Comment;
            $comment->user_id = $request->user()->id;
            $comment->pin_id = $request->pinId;
            $comment->content = $request->message;
            $comment->save();

            return response()->json("Successfully create comments", 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk megikuti atau membatalkan ikuti user lain (PUT)
    public function putFollowing($userId, Request $request) {
        try {
            if (!$request->user()->following->contains($userId)) {
                $request->user()->following()->attach($userId);

                return response()->json("Successfully follow", 200);
            } else {
                $request->user()->following()->detach($userId);

                return response()->json("Successfully unfollow", 200);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk menyukai detail pin (PUT)
    public function putLikePin($pinId, Request $request) {
        try {
            $pin = Pin::find($pinId);

            if ($pin) {
                if ($pin->likes()->where('user_id', $request->user()->id)->exists()) {
                    $pin->likes()->where('user_id', $request->user()->id)->delete();

                    return response()->json("Successfully unlike", 200);
                } else {
                    $like = $pin->likes()->firstOrCreate([
                        'user_id' => $request->user()->id,
                    ]);

                    // Get the owner of the pin
                    $owner = User::find($pin->user_id);

                    // Notify the owner
                    $owner->notify(new PinLiked($pin, $like->user_id));

                    return response()->json("Successfully like", 200);
                }
            } else {
                throw new \Exception("Pin not found");
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk melapor pin user (PUT)
    public function putReport($pinId, Request $request) {
        try {
            $reported = Report::where('user_id', $request->user()->id)->where('pin_id', $pinId)->exists();

            if (!$reported) {
                $report = new Report();
                $report->user_id = $request->user()->id;
                $report->pin_id = $pinId;
                $report->reason = !empty($request->reason) ? $request->reason : "Inapropriate";
                $report->save();

                return response()->json("Successfully report", 200);
            } else {
                $report = Report::where('user_id', $request->user()->id)->where('pin_id', $pinId);
                $report->delete();

                return response()->json("Successfully unreport", 200);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk mendapatkan notifikasi yang belum terbaca user login (GET)
    public function getUnreadNotificationsCount(Request $request) {
        try {
            $rowCount = DB::table('notifications')
                ->where('notifiable_id', $request->user()->id)
                ->whereNull('read_at')
                ->count();

            return response()->json(["unread" => $rowCount], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk mendapatkan data notifikasi user login (GET)
    public function getAllNotification(Request $request) {
        try {
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $json = [];

            foreach ($notifications as $data) {
                $datas = json_decode($data->data, true);

                $map = new \stdClass();
                $map->id = $data->id;
                $map->message = $datas["message"];
                $map->readAt = $data->read_at;
                $map->createdAt = $data->created_at;

                $json[] = $map;
            }

            return response()->json(["notifications" => $json], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk menandai notifikasi sudah dibaca semua (PUT)
    public function putReadAllNotification(Request $request) {
        try {
            $affectedRows = DB::table('notifications')
                ->where('notifiable_id', $request->user()->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            if ($affectedRows > 0) {
                return response()->json("Successfully marked all notifications as read", 200);
            } else {
                throw new \Exception("No action needed");
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk menandai notifikasi sudah dibaca (PUT)
    public function putReadNotification($notifiableId, Request $request) {
        try {
            $notif = DB::table('notifications')
                ->where('id', $notifiableId)->exists();

            if ($notif) {
                $affectedRows = DB::table('notifications')
                    ->where('id', $notifiableId) // Assuming $id is the notification ID
                    ->where('notifiable_id', $request->user()->id)
                    ->whereNull('read_at')
                    ->update(['read_at' => now()]);

                if ($affectedRows > 0) {
                    return response()->json("Successfully marked the notification as read", 200);
                } else {
                    throw new \Exception("No action needed");
                }
            } else {
                throw new \Exception("Notification not found");
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk mendapatkan data user login (GET)
    public function getUser(Request $request)
    {
        $user = $request->user();

        return response()->json($user);
    }

    // fungsi untuk mendapatkan data halaman account (GET)
    public function account(Request $request) {
        try {
            $user = $request->user();
            $pins = Pin::where('user_id', $user->id)->get();
            $likesCount = $user->likes->count();
            $followersCount = $user->followers->count();
            $followingCount = $user->following->count();

            $users = new \stdClass();
            $users->username = $user->username;
            $users->email = $user->email;
            $users->imageUrl = !empty($user->profile_photo_path) ? url(Storage::url($user->profile_photo_path)) : null;
            $users->pinsCount = $pins->count();
            $users->likesCount = $likesCount;
            $users->followersCount = $followersCount;
            $users->followingCount = $followingCount;

            $pin = [];

            foreach ($pins as $data) {
                $map = new \stdClass();
                $map->id = $data->id;
                $map->title = $data->title;
                $map->description = $data->description;
                $map->imageUrl = $data->image_path ? url(Storage::url('pins/' . $data->image_path)) : null;
                $map->link = $data->link;
                $map->tag = $data->tags;
                $map->likes = $user->hasLikedPin($data->id);

                $pin[] = $map;
            }

            return response()->json(['user' => $users, "pins" => $pin], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk menambahkan nama album user (POST)
    public function addAlbumName(Request $request) {
        try {
            Album::create([
                'user_id' => $request->user()->id,
                'name' => $request->name,
            ]);

            return response()->json("Successfully created new album", 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk mendapatkan data nama album user (GET)
    public function albumListName(Request $request) {
        try {
            $albums = Album::where('user_id', $request->user()->id)->get();

            $maps = [];

            foreach ($albums as $data) {
                $map = new \stdClass();
                $map->id = $data->id;
                $map->name = $data->name;

                $maps[] = $map;
            }

            return response()->json(['albums' => $maps], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk mendapatkan data album user beserta thumbnailnya (GET)
    public function pinAlbumThumbnailList(Request $request) {
        try {
            $albums = Album::where('user_id', $request->user()->id)->get();

            $maps = [];

            foreach ($albums as $data) {
                $map = new \stdClass();
                $map->id = $data->id;
                $map->name = $data->name;

                $thumbnail = $data->getCoverImageUrlAttribute();

                $map->thumbnail = $thumbnail !== 'thumbnail.jpg' ? url(Storage::url('pins/' . $thumbnail)) : null;

                $maps[] = $map;
            }

            return response()->json(['albumThumbnails' => $maps], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk mendapatkan data pin berdasarkan nama album user (GET)
    public function pinAlbumPhotoList($idAlbum, Request $request) {
        try {
            $album = Album::find($idAlbum);

            $pins = $album->pins()->get();

            $maps = [];

            foreach ($pins as $pin) {
                $map = new \stdClass();
                $map->id = $pin->id;
                $map->title = $pin->title;
                $map->description = $pin->description;
                $map->imageUrl = $pin->image_path ? url(Storage::url('pins/' . $pin->image_path)) : null;
                $map->link = $pin->link;
                $map->tags = $pin->tags;

                $maps[] = $map;
            }

            return response()->json(['pinAlbumPhotos' => $maps], 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk menambah atau menghapus pin ke dalam album user (POST)
    public function saveOrRemovePinInAlbum(Request $request) {
        try {
            $album = Album::find($request->albumId);

            if ($album->user_id != $request->user()->id) {
                throw new \Exception("You do not have permission to add to this album.");
            }

            $pin = Pin::find($request->pinId);

            if ($pin && !$album->pins->contains($pin)) {
                $album->pins()->attach($pin);

                return response()->json("Successfully saved pin to $album->name Album", 200);
            } else {
                $album->pins()->detach($pin);

                return response()->json("Successfully remove pin from $album->name Album", 200);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    // fungsi untuk Download Pin Image (POST)
    public function downloadPinImage(Request $request) {
        try {
            $pin = Pin::find($request->pinId);

            if ($pin != null) {
                $filePath = storage_path('app/public/pins/' . $pin->image_path);

                if (file_exists($filePath)) {
                    $fileContent = file_get_contents($filePath);

                    $timeNow = time();

                    $fileInfo = pathinfo($filePath);

                    return response()->make($fileContent, 200, [
                        'Content-Type' => mime_content_type($filePath),
                        'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
                        'Content-Name' => $pin->title . '_' . $timeNow . '.' . $fileInfo['extension'],
                    ]);
                } else {
                    return response()->json('File not found.', 404);
                }
            } else {
                return response()->json('File not found.', 404);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
