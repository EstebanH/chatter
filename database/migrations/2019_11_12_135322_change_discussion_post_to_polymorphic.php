<?php

use DevDojo\Chatter\Models\Discussion;
use DevDojo\Chatter\Models\Models;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeDiscussionPostToPolymorphic extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('chatter_post', function (Blueprint $table) {
			$table->dropForeign(['chatter_discussion_id']);

			$table->renameColumn('chatter_discussion_id', 'discussion_id');

			$table->string('discussion_type')->default(addslashes(Models::className(Discussion::class)))->after('chatter_discussion_id');

			$table->index(['discussion_type', 'discussion_id']);
		});

		Schema::table('chatter_post', function (Blueprint $table) {
			$table->string('discussion_type')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('chatter_post', function (Blueprint $table) {
			$table->dropIndex(['discussion_type', 'discussion_id']);
			$table->dropColumn('discussion_type');

			$table->renameColumn('discussion_id', 'chatter_discussion_id');

			$table->foreign('chatter_discussion_id')->references('id')->on('chatter_discussion')
				->onDelete('cascade')
				->onUpdate('cascade');
		});
	}
}
