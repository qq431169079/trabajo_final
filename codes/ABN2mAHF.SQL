$sql = 
										
										'SELECT 
											'.TOPICS_TABLE.'.ID, 
											'.TOPICS_TABLE.'.TopicName, 
											'.TOPICS_TABLE.'.TopicDescription,
											'.TOPICS_TABLE.'.TopicViews,
											'.POSTS_TABLE.'.PostDate as DateCreated, 
											(SELECT COUNT(*) FROM '.POSTS_TABLE.' WHERE '.POSTS_TABLE.'.TopicID = '.TOPICS_TABLE.'.ID) as TotalPosts, 
											(SELECT MAX(PostDate) FROM '.POSTS_TABLE.' WHERE '.POSTS_TABLE.'.TopicID = '.TOPICS_TABLE.'.ID) as LastPostDate,
											(
												SELECT posts.ID FROM posts
												WHERE posts.TopicID = topics.ID
												ORDER BY PostDate DESC LIMIT 1
											) as LastPostID,
											(
												SELECT '.USERS_TABLE.'.ID
												FROM '.POSTS_TABLE.'
												LEFT JOIN '.USERS_TABLE.'
												ON '.USERS_TABLE.'.ID = '.POSTS_TABLE.'.AuthorID
												WHERE '.POSTS_TABLE.'.TopicID = '.TOPICS_TABLE.'.ID
												ORDER BY '.POSTS_TABLE.'.PostDate DESC
												LIMIT 1
											) as LastPostAuthorID,
											(
												SELECT '.USERS_TABLE.'.ForumName
												FROM '.POSTS_TABLE.'
												LEFT JOIN '.USERS_TABLE.'
												ON '.USERS_TABLE.'.ID = '.POSTS_TABLE.'.AuthorID
												WHERE '.POSTS_TABLE.'.TopicID = '.TOPICS_TABLE.'.ID
												ORDER BY '.POSTS_TABLE.'.PostDate DESC
												LIMIT 1
											) as LastPostAuthorName,
											(
												SELECT '.USERS_TABLE.'.ID
												FROM '.POSTS_TABLE.'
												LEFT JOIN '.USERS_TABLE.'
												ON '.USERS_TABLE.'.ID = '.POSTS_TABLE.'.AuthorID
												WHERE '.POSTS_TABLE.'.TopicID = '.TOPICS_TABLE.'.ID
												ORDER BY '.POSTS_TABLE.'.PostDate ASC
												LIMIT 1
											) as AuthorID,
											(
												SELECT '.USERS_TABLE.'.ForumName
												FROM '.POSTS_TABLE.'
												LEFT JOIN '.USERS_TABLE.'
												ON '.USERS_TABLE.'.ID = '.POSTS_TABLE.'.AuthorID
												WHERE '.POSTS_TABLE.'.TopicID = '.TOPICS_TABLE.'.ID
												ORDER BY '.POSTS_TABLE.'.PostDate ASC
												LIMIT 1
											) as AuthorName
										FROM '.TOPICS_TABLE.' 
										LEFT JOIN '.POSTS_TABLE.' 
										ON '.TOPICS_TABLE.'.ID = '.POSTS_TABLE.'.TopicID AND '.TOPICS_TABLE.'.FirstPostID = '.POSTS_TABLE.'.ID
										WHERE ForumID = '.$ForumID.'
										ORDER BY LastPostDate DESC';