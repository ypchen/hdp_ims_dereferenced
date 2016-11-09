<?php
	require('00_prefix.php');
	$myName = basename($myScriptName, '.php');
	$myBaseName = $myName;
	$titleComponents = explode('.', $myBaseName);
	$pageTitleBaseElements = explode('__', $titleComponents[0]);
	$pageTitleBase = $pageTitleBaseElements[0];

	// Sites that are turned off
	if (strcasecmp($imsDirectory, 'video') == 0) {
		$sitesTurnedOff = explode(',', $imsTurnOffVideoSites);
	}
	else {
		$sitesTurnedOff = explode(',', $imsTurnOffAdultSites);
	}
	$myNameComponents = explode('.', $myName);
	if (in_array($myNameComponents[0], $sitesTurnedOff)) {
		header('Object not found', true, 404);
	}
	else {
?>
<?php
	echo "<?xml version=\"1.0\" encoding=\"UTF8\" ?>\r\n";
	echo "<rss version=\"2.0\" xmlns:dc=\"http://purl.org/dc/elements/1.1/\">\r\n";
?>
<?php
	include('06_get.query.inc');

	ini_set('user_agent', $userAgent);

	if (isset($extra)) {
		// $extra may be changed by included scripts
		$extra_01_base = $extra;
	}
	else {
		unset($extra_01_base);
	}

	$itemTotal  = 0;
	$pass_check = true;
	try {
		include($myName . '.1.inc');
	}
	catch (Exception $e) {
		$pass_check = false;
	}

	// Default display parameters
	if (!isset($themeMainForegroundColor)) $themeMainForegroundColor = '255:255:255';
	if (!isset($themeMainBackgroundColor)) $themeMainBackgroundColor = '10:105:150';
	if (!isset($themeTextForegroundColor)) $themeTextForegroundColor = '255:255:255';
	if (!isset($themeTextBackgroundColor)) $themeTextBackgroundColor = '0:0:0';
	if (!isset($themeTipsForegroundColor)) $themeTipsForegroundColor = '255:255:0';
	if (!isset($themeTipsBackgroundColor)) $themeTipsBackgroundColor = $themeTextBackgroundColor;
	if (!isset($themeVersionForegroundColor)) $themeVersionForegroundColor = '0:140:140';
	if (!isset($themeVersionBackgroundColor)) $themeVersionBackgroundColor = '-1:-1:-1';
	if (!isset($themeItemForegroundColorFocused)) $themeItemForegroundColorFocused = $themeTextForegroundColor;
	if (!isset($themeItemBackgroundColorFocused)) $themeItemBackgroundColorFocused = $themeMainBackgroundColor;
	if (!isset($themeItemForegroundColorUnfocused)) $themeItemForegroundColorUnfocused = '140:140:140';
	if (!isset($themeItemBackgroundColorUnfocused)) $themeItemBackgroundColorUnfocused = $themeTextBackgroundColor;
	if (!isset($themeItemFontSizeFocused)) $themeItemFontSizeFocused = '16';
	if (!isset($themeItemFontSizeUnfocused)) $themeItemFontSizeUnfocused = $themeItemFontSizeFocused;
	if (!isset($themeTipsFontSize)) $themeTipsFontSize = '16';
	if (!isset($themeVersionFontSize)) $themeVersionFontSize = '10';

	// Create my own link
	$params  = str_replace('&', '&amp;', $_SERVER['QUERY_STRING']);
	$currUrl = $scriptsURLprefix . '/' . $myName . '.php?' . $params;
?>

<onEnter>
	focus = 0;
	message = "";
	inputNumCount = 0;
	inputNumVal = -1;
	curNumVal = -1;

	dataBrowse   = "<?php echo $fileBrowse; ?>";
	dataWatch    = "<?php echo $fileWatch; ?>";
	dataFavorite = "<?php echo $fileFavorite; ?>";

	dataBrowseMax   = <?php echo $maxBrowse; ?>;
	dataWatchMax    = <?php echo $maxWatch; ?>;
	dataFavoriteMax = <?php echo $maxFavorite; ?>;

	history = <?php echo $history; ?>;
	historyTips = "";
	if (history == 0) {
		/* Static items */
		itemCount = getPageInfo("itemCount");
		setRefreshTime(200);
	}
	else {
		/* 最近瀏覽 history == 1 */
		/* 最近觀看 history == 2 */
		/* 本地收藏 history == 3 */

		dataFileThisPage = "<?php echo $history_filename; ?>";

		historyTips = " [停止]移上 [播放]移下 [紅]刪除項目 [黃]刪除全部";

		userMenuFile = getStoragePath("tmp") + "ims.<?php echo $imsDirectory; ?>.history." + history + ".rss.dat";
		userMenuItem = readStringFromFile(userMenuFile);
		if (userMenuItem != null) {
			userMenuItem = Integer(userMenuItem);
		}
		else {
			userMenuItem = 0;
		}
		setFocusItemIndex(userMenuItem);

		dataFile   = dataFileThisPage;
		dataArray  = readStringFromFile(dataFile);
		typeArray  = null;
		titleArray = null;
		linkArray  = null;
		itemSize = 0;
		k = 0;
		while (getStringArrayAt(dataArray, k) != null) {
			typeArray  = pushBackStringArray(typeArray,  getStringArrayAt(dataArray, k));
			k = k+1;
			titleArray = pushBackStringArray(titleArray, getStringArrayAt(dataArray, k));
			k = k+1;
			linkArray  = pushBackStringArray(linkArray,  getStringArrayAt(dataArray, k));
			k = k+1;
			itemSize = itemSize+1;
		}

		/* Dynamic items */
		itemCount = itemSize;
	}

	x = itemCount;
	<?php include('00_utils.digits.inc'); ?>
	itemCountDigits = y;
</onEnter>

<onRefresh>
	setRefreshTime(-1);
	itemCount = getPageInfo("itemCount");
	x = itemCount;
	<?php include('00_utils.digits.inc'); ?>
	itemCountDigits = y;
	redrawDisplay();
</onRefresh>

<mediaDisplay name="threePartsView"
	showHeader="no"
	showDefaultInfo="no"
	autoSelectMenu="no"
	autoSelectItem="no"
	selectMenuOnRight="no"
	itemXPC="<?php echo $itemXPC; ?>"
	itemYPC="<?php echo $itemYPC; ?>"
	itemWidthPC="<?php echo $itemWidthPC; ?>"
	itemHeightPC="<?php echo $itemHeightPC; ?>"
	itemPerPage="<?php echo $itemPerPage; ?>"
	itemImageWidthPC="0"
	itemImageHeightPC="0"
	itemGap="0"
	sliding="no"
	capXPC="<?php echo $itemXPC; ?>"
	capYPC="<?php echo $itemYPC; ?>"
	capWidthPC="50"
	capHeightPC="64"
	sideLeftWidthPC="0"
	sideRightWidthPC="0"
	headerImageWidthPC="0"
	backgroundColor="0:0:0"
	itemBackgroundColor="0:0:0"
	bottomYPC="90"
	imageFocus=""
	idleImageWidthPC="10"
	idleImageHeightPC="10"
	itemImageXPC="0"
	unFocusFontColor="140:140:140"
	focusFontColor="255:255:255"
	popupXPC = "40"
	popupYPC = "55"
	popupWidthPC = "22.3"
	popupHeightPC = "5.5"
	popupFontSize = "13"
	popupBorderColor="28:35:51"
	popupForegroundColor="255:255:255"
	popupBackgroundColor="28:35:51"
>
	<image redraw="no"
		offsetXPC="5" offsetYPC="2.5"
		widthPC="15" heightPC="15"
		backgroundColor="-1:-1:-1">
		<script>
			imgLeftTop;
		</script>
	</image>

	<text align="center" fontSize="26"
		offsetXPC="0" offsetYPC="0"
		widthPC="100" heightPC="20"
		backgroundColor="<?php echo $themeMainBackgroundColor; ?>"
		foregroundColor="<?php echo $themeMainForegroundColor; ?>">
		<script>
			getPageInfo("pageTitle");
		</script>
	</text>

	<text redraw="yes" fontSize="20"
		offsetXPC="82" offsetYPC="13.5"
		widthPC="20" heightPC="6"
		backgroundColor="<?php echo $themeMainBackgroundColor; ?>"
		foregroundColor="<?php echo $themeMainForegroundColor; ?>">
		<script>
			"" + Add(focus, 1) + " / " + itemCount;
		</script>
	</text>

	<text redraw="yes" align="left"
		fontSize="16" lines="5"
		offsetXPC="60" offsetYPC="<?php echo $itemYPC+(((7*$itemHeightPC)-$myImgHeight)/2); ?>"
		widthPC="<?php echo $myImgWidth; ?>" heightPC="<?php echo $myImgHeight; ?>"
		backgroundColor="0:0:0">
		<script>
			"";
		</script>
	</text>

	<image redraw="yes"
		offsetXPC="60" offsetYPC="<?php echo $itemYPC+(((7*$itemHeightPC)-$myImgHeight)/2); ?>"
		widthPC="<?php echo $myImgWidth; ?>" heightPC="<?php echo $myImgHeight; ?>"
		backgroundColor="0:0:0">
		<script>
			if (imgSpecial == null)	img;
			else imgSpecial;
		</script>
	</image>

	<text redraw="yes" align="left"
		fontSize="16" lines="5"
		offsetXPC="59" offsetYPC="<?php echo ($itemYPC+(7*$itemHeightPC)); ?>"
		widthPC="<?php echo ($myImgWidth+1); ?>" heightPC="<?php echo (4*$itemHeightPC)+1; ?>"
		backgroundColor="<?php echo $themeTextBackgroundColor; ?>"
		foregroundColor="<?php echo $themeTextForegroundColor; ?>">
		<script>
			"";
		</script>
	</text>

	<text redraw="yes" align="left"
		fontSize="16" lines="5"
		offsetXPC="59" offsetYPC="<?php echo ($itemYPC+(7*$itemHeightPC)); ?>"
		widthPC="<?php echo ($myImgWidth+1); ?>" heightPC="<?php echo (4*$itemHeightPC); ?>"
		backgroundColor="<?php echo $themeTextBackgroundColor; ?>"
		foregroundColor="<?php echo $themeTextForegroundColor; ?>">
		<script>
			if (msgSpecialNote == null)	note;
			else msgSpecialNote;
		</script>
	</text>

	<text redraw="no" align="left"
		fontSize="<?php echo $themeTipsFontSize; ?>" lines="1"
		offsetXPC="0" offsetYPC="<?php echo ($itemYPC+(11*$itemHeightPC)); ?>"
		widthPC="100" heightPC="<?php echo $itemHeightPC; ?>"
		backgroundColor="<?php echo $themeTipsBackgroundColor; ?>"
		foregroundColor="<?php echo $themeTipsForegroundColor; ?>">
		<script>
			if ((inputNumCount == 0) ||
					((inputNumCount == itemCountDigits) &amp;&amp;
					((curNumVal &lt; 1) || (curNumVal &gt; itemCount)))) {
				str = "[↕↔上下頁]移動" + historyTips + " [數字直選]";
			}
			else {
				str = "[↕↔上下頁]移動" + historyTips + " 第 " + curNumVal + " 項";
			}
			str + message;
		</script>
	</text>

	<text redraw="no" align="right"
		fontSize="<?php echo $themeVersionFontSize; ?>" lines="1"
		offsetXPC="88" offsetYPC="<?php echo ($itemYPC+(11*$itemHeightPC)); ?>"
		widthPC="9" heightPC="<?php echo ($itemHeightPC*1.4); ?>"
		backgroundColor="<?php echo $themeVersionBackgroundColor; ?>"
		foregroundColor="<?php echo $themeVersionForegroundColor; ?>">
		<script>
			"<?php echo $imsVersion; ?>";
		</script>
	</text>

	<text redraw="yes" align="center"
		fontSize="22"
		offsetXPC="0" offsetYPC="90"
		widthPC="100" heightPC="8"
		backgroundColor="<?php echo $themeMainBackgroundColor; ?>"
		foregroundColor="<?php echo $themeMainForegroundColor; ?>">
		<script>
			if (msgSpecial == null)	itemTitle;
			else msgSpecial;
		</script>
	</text>

	<idleImage>image/<?php echo $idleImagePrefix; ?>1.png</idleImage>
	<idleImage>image/<?php echo $idleImagePrefix; ?>2.png</idleImage>
	<idleImage>image/<?php echo $idleImagePrefix; ?>3.png</idleImage>
	<idleImage>image/<?php echo $idleImagePrefix; ?>4.png</idleImage>
	<idleImage>image/<?php echo $idleImagePrefix; ?>5.png</idleImage>
	<idleImage>image/<?php echo $idleImagePrefix; ?>6.png</idleImage>
	<idleImage>image/<?php echo $idleImagePrefix; ?>7.png</idleImage>
	<idleImage>image/<?php echo $idleImagePrefix; ?>8.png</idleImage>

	<itemDisplay>
		<text align="left" lines="1" offsetXPC="0" offsetYPC="0" widthPC="100" heightPC="100">
			<script>
				idx = getQueryItemIndex();
				focus = getFocusItemIndex();
				if(focus == idx) {
					itemTitle = getItemInfo(idx, "title");
					note = getItemInfo(idx, "note");
					img = getItemInfo(idx, "image");
					if (img == null) {
						img = "<?php echo siteImage($myBaseName); ?>";
					}
					imgLeftTop = getItemInfo(idx, "channelImage");
					if (imgLeftTop == null) {
						imgLeftTop = "<?php echo siteImage($myBaseName); ?>";
					}
				}

				strItemTitle = "" + Add(idx, 1) + ":　" + getItemInfo(idx, "title");
				numBoundary  = 9;
				maxDigits    = itemCountDigits;
				if (maxDigits &lt; 2) maxDigits = 2;
				while (maxDigits &gt; 1) {
					if (idx &lt; numBoundary) {
						strItemTitle = "0" + strItemTitle;
					}
					numBoundary = (10 * numBoundary) + 9;
					maxDigits -= 1;
				}
				strItemTitle;
			</script>
			<fontSize>
				<script>
					idx = getQueryItemIndex();
					focus = getFocusItemIndex();
					if(focus == idx) "<?php echo $themeItemFontSizeFocused; ?>";
					else "<?php echo $themeItemFontSizeUnfocused; ?>";
				</script>
			</fontSize>
			<foregroundColor>
				<script>
					idx = getQueryItemIndex();
					focus = getFocusItemIndex();
					if(focus == idx) "<?php echo $themeItemForegroundColorFocused; ?>";
					else "<?php echo $themeItemForegroundColorUnfocused; ?>";
				</script>
			</foregroundColor>
			<backgroundColor>
				<script>
					idx = getQueryItemIndex();
					focus = getFocusItemIndex();
					if(focus == idx) "<?php echo $themeItemBackgroundColorFocused; ?>";
					else "<?php echo $themeItemBackgroundColorUnfocused; ?>";
				</script>
			</backgroundColor>
		</text>
	</itemDisplay>

	<onUserInput>
		<script>
			ret = "false";
			message = "";
			userInput = currentUserInput();

			idx = Integer(getFocusItemIndex());
			if (
				(userInput == "pagedown") ||
				(userInput == "pageup") ||
				(userInput == "left") ||
				(userInput == "right") ||
				(userInput == "one") ||
				(userInput == "two") ||
				(userInput == "three") ||
				(userInput == "four") ||
				(userInput == "five") ||
				(userInput == "six") ||
				(userInput == "seven") ||
				(userInput == "eight") ||
				(userInput == "nine") ||
				(userInput == "zero")
			) {
				if (userInput == "pagedown") {
					idx = itemCount-1;
				}
				else if (userInput == "pageup") {
					idx = 0;
				}
				else if (userInput == "right") {
					idx -= -<?php echo $itemPerPage; ?>;
					if(idx &gt;= itemCount) idx = itemCount-1;
				}
				else if (userInput == "left") {
					idx -= <?php echo $itemPerPage; ?>;
					if(idx &lt; 0) idx = 0;
				}
				else {
					if (userInput == "one") {
						inputNumVal = 1;
					}
					else if (userInput == "two") {
						inputNumVal = 2;
					}
					else if (userInput == "three") {
						inputNumVal = 3;
					}
					else if (userInput == "four") {
						inputNumVal = 4;
					}
					else if (userInput == "five") {
						inputNumVal = 5;
					}
					else if (userInput == "six") {
						inputNumVal = 6;
					}
					else if (userInput == "seven") {
						inputNumVal = 7;
					}
					else if (userInput == "eight") {
						inputNumVal = 8;
					}
					else if (userInput == "nine") {
						inputNumVal = 9;
					}
					else if (userInput == "zero") {
						inputNumVal = 0;
					}

					if ((inputNumCount == 0) || (inputNumCount == itemCountDigits)) {
						inputNumCount = 1;
						curNumVal = inputNumVal;
					}
					else {
						inputNumCount = inputNumCount + 1;
						curNumVal = (10*curNumVal) + inputNumVal;
					}

					if ((curNumVal &gt;= 1) &amp;&amp; (curNumVal &lt;= itemCount)) {
						idx = (curNumVal - 1);
					}
					else if ((inputNumVal &gt;= 1) &amp;&amp; (inputNumVal &lt;= itemCount)) {
						/* Keep the last digit which makes the value out of range unless invalid */
						inputNumCount = 1;
						curNumVal = inputNumVal;
						idx = (curNumVal - 1);
					}
					else {
						inputNumCount = 0;
						inputNumVal = -1;
						curNumVal = -1;
					}
				}
				setFocusItemIndex(idx);
				redrawDisplay();
				ret = "true";
			}
			else if (history &gt; 0) {
				if (userInput == "enter") {
					if ((history == 2) || (history == 3)) {
						if (history == 3) {
							writeStringToFile(userMenuFile, idx);
						}
						if (Integer(getStringArrayAt(typeArray, idx)) == 0) {

							showIdle();
							realURL = getStringArrayAt(linkArray, idx);

							/* Parameters */
							dataFile   = dataWatch;
							dataMax    = dataWatchMax;
							dataType   = "0";
							dataTitle  = getStringArrayAt(titleArray, idx);
							dataLink   = realURL;
							<?php include('08_history.record.inc'); ?>

							/* Play the URL */
							playItemURL(realURL, 0);
							cancelIdle();

							/* refresh this page */
							jumpToLink("refreshItem");
							redrawDisplay();

							ret = "true";
						}
					}
				}
				else if (userInput == "option_red") {
					dataFile  = dataFileThisPage;
					dataArray = readStringFromFile(dataFile);
					strIdx    = Add(Add(Add(idx, idx), idx), 2);
					dataArray = deleteStringArrayAt(dataArray, strIdx);
					strIdx    = strIdx-1;
					dataArray = deleteStringArrayAt(dataArray, strIdx);
					strIdx    = strIdx-1;
					dataArray = deleteStringArrayAt(dataArray, strIdx);
					writeStringToFile(dataFile, dataArray);

					/* refresh this page */
					jumpToLink("refreshItem");
					redrawDisplay();

					ret = "true";
				}
				else if (userInput == "option_yellow") {
					dataFile  = dataFileThisPage;
					dataArray = null;
					writeStringToFile(dataFile, dataArray);

					/* refresh this page */
					jumpToLink("refreshItem");
					redrawDisplay();

					ret = "true";
				}
				else if (userInput == "video_stop") {
					if (idx &gt; 0) {
						dataFile   = dataFileThisPage;
						dataIdx    = (idx-1);
						<?php include('08_history.swap.inc'); ?>

						typeArray  = dataTypes;
						titleArray = dataTitles;
						linkArray  = dataLinks;
						setFocusItemIndex(dataIdx);

						redrawDisplay();
					}
					ret = "true";
				}
				else if (userInput == "video_play") {
					if (idx &lt; (itemCount-1)) {
						dataFile   = dataFileThisPage;
						dataIdx    = idx;
						<?php include('08_history.swap.inc'); ?>

						typeArray  = dataTypes;
						titleArray = dataTitles;
						linkArray  = dataLinks;
						setFocusItemIndex(dataIdx-(-1));

						redrawDisplay();
					}
					ret = "true";
				}
			}
			ret;
		</script>
	</onUserInput>
</mediaDisplay>

<getInputFromUser>
	inputPrefs = readStringFromFile("<?php echo $fileLocalInputPrefs; ?>");
	input = null;
	if ((inputPrefs == null) || (inputPrefs == "")
	 || (((inputType = getStringArrayAt(inputPrefs, 0)) != "1") &amp;&amp; (inputType != "2"))
	 || ((inputMethod = getStringArrayAt(inputPrefs, 1)) == null)
	 || (inputMethod == "")) {
		input = getInput("Enter a keyword");
	}
	else {
		input = doModalRss(inputMethod, "mediaDisplay", "search", 0);
	}
</getInputFromUser>

<?php
	if ($history > 0) {
?>
	<item_template>
		<displayTitle><script>getStringArrayAt(titleArray, -1);</script></displayTitle>
		<title><script>getStringArrayAt(titleArray, -1);</script></title>
		<link><script>getStringArrayAt(linkArray, -1);</script></link>
		<mediaDisplay />
	</item_template>
<?php } ?>

<?php include($myName . '.2.inc'); ?>

<?php
	// refresh this page
	echo "<refreshItem>\r\n";
	echo "\t<link>$currUrl</link>\r\n";
	echo "</refreshItem>\r\n";
?>

</rss>
<?php
	}

	require('00_suffix.php');
?>
