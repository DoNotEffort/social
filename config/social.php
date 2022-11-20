<?php

return [
	"telegram"=>[
		/**
		 * SCHEMA:
		  "message_id": <integer>,
        "from": {
            "id": <integer>,
            "is_bot": <boolean,
            "first_name": <string>,
            "username": <string>
        },
        "chat": {
            "id": <integer>,
            "title": <string>,
            "type": "group | supergroup | private",
            "all_members_are_administrators": <boolean>
        },
        "date": <unix-timestamp>,
        "text": <string>
		 */
		
		"schema"=>[
				"add"=>[
					"id",
					"is_bot",
					"first_name",
					"username"
				],
				"post"=>[
						"message_id",
						"from.id",
						"from.username",
						"chat.id",
						"chat.title",
						"date"
				],
			],
			"transform"=>[
				"keys"=>[
					"post"=>[
						"message_id"=>"id",
						"from.id"=>"user.id",
						"from.username"=>"user.username",
						"chat.id"=>"target.id",
						"chat.title"=>"target.title",
					]
				],
			]
	],
	"twitter"=>[
		"redirect_uri"=> env("TWITTER_REDIRECT_URI"),
		"tokens"=>[
				"consumer_key"=>env("TWITTER_CONSUMER_KEY"),
				"consumer_secret"=>env("TWITTER_CONSUMER_SECRET"),
		],
		/*
			{
					"created_at": "Thu Sep 15 18:51:50 +0000 2022",
					"id": 1570485562104061952,
					"id_str": "1570485562104061952",
					"text": "qqq",
					"truncated": false,
					"entities": {
							"hashtags": [],
							"symbols": [],
							"user_mentions": [],
							"urls": []
					},
					"source": "<a href=\"https://medya.dijitalinmerkezi.com/\" rel=\"nofollow\">Dijitalin Merkezi</a>",
					"in_reply_to_status_id": null,
					"in_reply_to_status_id_str": null,
					"in_reply_to_user_id": null,
					"in_reply_to_user_id_str": null,
					"in_reply_to_screen_name": null,
					"user": {
							"id": 1244048919610482688,
							"id_str": "1244048919610482688",
							"name": "Vine Paylaşım",
							"screen_name": "vinepaylasimtr",
							"location": "",
							"description": "Youtube Kanalı:\nhttps://t.co/me7f1FPdpO",
							"url": "https://t.co/me7f1FPdpO",
							"entities": {
									"url": {
											"urls": [
													{
															"url": "https://t.co/me7f1FPdpO",
															"expanded_url": "https://www.youtube.com/channel/UCvRALvKBx3DUKpkukyHDjJQ",
															"display_url": "youtube.com/channel/UCvRAL…",
															"indices": [
																	0,
																	23
															]
													}
											]
									},
									"description": {
											"urls": [
													{
															"url": "https://t.co/me7f1FPdpO",
															"expanded_url": "https://www.youtube.com/channel/UCvRALvKBx3DUKpkukyHDjJQ",
															"display_url": "youtube.com/channel/UCvRAL…",
															"indices": [
																	16,
																	39
															]
													}
											]
									}
							},
							"protected": false,
							"followers_count": 1,
							"friends_count": 0,
							"listed_count": 0,
							"created_at": "Sat Mar 28 23:49:18 +0000 2020",
							"favourites_count": 0,
							"utc_offset": null,
							"time_zone": null,
							"geo_enabled": false,
							"verified": false,
							"statuses_count": 124,
							"lang": null,
							"contributors_enabled": false,
							"is_translator": false,
							"is_translation_enabled": false,
							"profile_background_color": "F5F8FA",
							"profile_background_image_url": null,
							"profile_background_image_url_https": null,
							"profile_background_tile": false,
							"profile_image_url": "http://pbs.twimg.com/profile_images/1336689946875338753/4Z8IP0fo_normal.jpg",
							"profile_image_url_https": "https://pbs.twimg.com/profile_images/1336689946875338753/4Z8IP0fo_normal.jpg",
							"profile_banner_url": "https://pbs.twimg.com/profile_banners/1244048919610482688/1607526694",
							"profile_link_color": "1DA1F2",
							"profile_sidebar_border_color": "C0DEED",
							"profile_sidebar_fill_color": "DDEEF6",
							"profile_text_color": "333333",
							"profile_use_background_image": true,
							"has_extended_profile": false,
							"default_profile": true,
							"default_profile_image": false,
							"following": false,
							"follow_request_sent": false,
							"notifications": false,
							"translator_type": "none",
							"withheld_in_countries": []
					},
					"geo": null,
					"coordinates": null,
					"place": null,
					"contributors": null,
					"is_quote_status": false,
					"retweet_count": 0,
					"favorite_count": 0,
					"favorited": false,
					"retweeted": false,
					"lang": "qst"
			}

		*/
		"schema"=>[
			"post"=>[
				"created_at",
				"id",
				"user.id",
				"user.name",
				"user.screen_name",
				"user.profile_image_url_https"
				]
			],
		"transform"=>[
			"keys"=>[
				"post"=>[
					"created_at"=>"date",
					"user.screen_name"=>"user.username",
					"user.profile_image_url_https"=>"user.image"
				]
				],
			"values"=>[
					"post"=>[
						"date"=>function ($value) {
							return strtotime($value);
						}
					]
				]
		]
	]
];