How to build newsletter subscriber system on Laravel 10
Newsletter section is one of the important parts to interact with visitor. In todays article, I will show you how to build a newsletter system on laravel 10.

What you need?

A laravel application
A mailtrap account
#Table of Content
#Scenario
This is my plan:

User can able to submit email to /subscribe route. - Upon submission, a job will be dispatch to send email
In the email, user is able to verify email to /subscribe/<<hash>> route.
Send an welcome email.
#Write Code
Let's write code now.

#Create table, model, settings, and migration
I want to create table for subscribers and model with Subscriber.

php artisan make:model Subscriber -m
It will create migration and model file together.

Update your table database/migrations/{***********}_create_subscribers_table.php

    ...

    public function up(): void
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('hash')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }
Make your model Subscriber.php guarded nothing.

class Subscriber extends Model
{
    use HasFactory;

+    protected $fillable = [
+        'email',
+        'hash',
+        'verified_at',
+    ];
}
Setup your database

In .env file:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=<<DB_NAME>>
DB_USERNAME=<<DB_USER_NAME>>
DB_PASSWORD=<<DB_PASSWORD>>
Run migration In the terminal, run this:

php artisan migrate
#Route
We need at least two routes to achieve our goal.

route/web.php

use App\Http\Controllers\SubscribeController;

Route::post('/subscribe', [SubscribeController::class, 'post']);
Route::get('/subscribe/{hash}', [SubscribeController::class, 'verify'])->name('subscribe.confirm');
#Write Controller
In the controller, we need write logic now.

Create a form request

php artisan make:request NewsletterRequest
Update your App\Http\Requests\NewsletterRequest

class NewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'email|unique:email|min:6|max:64'
        ];
    }
}
Create & Update your controller now

php artisan make:controller SubscribeController
Now, update the controller.

class SubscribeController extends Controller
{
    public function post(NewsletterRequest $request)
    {
        $validated = $request->validated();

        // Maybe you need more validation rules???
        $Subscriber = Subscriber::create([
            'email' => $validated['email'],
            'hash' => md5($validated['email']),
        ]);

        SubscriberJoinJob::dispatch($Subscriber);

        return redirect()->back()->with('success', 'You have successfully subscribed. Please check your email spam folder.');
    }

    public function verify(string $hash)
    {
        $subscriber = Subscriber::where('hash', $hash)->firstOrFail();

        $subscriber->update([
            'hash' => null,
            'verified_at' => now()
        ]);

        return redirect('/')
            ->with('success', 'You have successfully verified your email.');
    }
}
ℹ️ Feel free to update your code/logic.

Create job

You need to create a job:

php artisan make:job SubscriberJoinJob
Now, update your job App\Jobs\SubscriberJoinJob:

class SubscriberJoinJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Subscriber $subscriber)
    {
    }

    public function handle(): void
    {
        Mail::to($this->subscriber->email)
            ->send(new UserJoin($this->subscriber));
    }
}
Crate a mailable class

Run this command to create a mailable class:

php artisan make:mail UserJoin
Update the mailable class App\Mail\UserJoin:

class UserJoin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Subscriber $user)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'User Join',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'subscriber',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
Check more about mail: https://laravel.com/docs/10.x/mail#sending-mail

#Update Mail Setup
As I mentioned that, I will use mailtrap for testing email, so you need to setup in the .env file.

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=2525
MAIL_USERNAME= # value from mailtrap
MAIL_PASSWORD= # value from mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS= # set the sender email address
MAIL_FROM_NAME="${APP_NAME}"
ℹ️ Check more details how to setup

#Setup Queue
ℹ️ You don't have to do anything if you don't want to queue the email.

If you want to queue the email, then you need to setup the config, migrate the db, and start the queue worker.

Migrate Database Run the following command:

php artisan queue:table

php artisan migrate
Ref: https://laravel.com/docs/10.x/queues#driver-prerequisites

Setup the config Open .env file, and update QUEUE_CONNECTION value.

QUEUE_CONNECTION=database
Ref: https://laravel.com/docs/10.x/queues#driver-prerequisites

Start the Queue Worker Since your job is in the queue, someone needs to process it. Therefore you need a queue worker who will process the task. To process the task, simply run this command in the terminal and keep it open.

php artisan queue:work