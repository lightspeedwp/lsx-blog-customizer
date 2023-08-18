# Change log

## [[1.4.7]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.4.7) - 2023-08-18

### Security
- General testing to ensure compatibility with latest WordPress version (6.3).

## [[1.4.6]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.4.6) - 2023-04-20

### Security
- General testing to ensure compatibility with latest WordPress version (6.2).

## [[1.4.5]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.4.5) - 2022-12-22

### Security
- General testing to ensure compatibility with latest WordPress version (6.1.1).

## [[1.4.4]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.4.4) - 2022-05-25

### Security
- General testing to ensure compatibility with latest WordPress version (6.0).

### Fixed
- The category selection for the LSX Blog Posts widget


## [[1.4.3]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.4.3) - 2021-04-22

### Added
- The ability to select the tag or category or both, in used of the post relation query.
- A filter `lsx_blog_customizer_related_posts_taxonomies` to allow you to add any custom taxonomies to the relation array.

### Security
- General testing to ensure compatibility with latest WordPress version (5.7).

## [[1.4.2]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.4.2) - 2021-04-13

### Fixed
- The triggering of the customizer options
- Default blog spacing above the content, when LSX search is disabled.
- The correct variable is now being sent to the `lsx_blog_customizer_show_switcher` filter.

## [[1.4.1]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.4.1) - 2020-10-04

### Updated
- Documentation and support links.

### Security
- General testing to ensure compatibility with latest WordPress version (5.6).

## [[1.4.0]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.4.0) - 2020-10-04

### Added
- Added default WP 5.5 lazy loading.
- Added a 2 columns layout option for the blog.
- Fixed spacing for the archive layouts.
- Added in 2 filters to allow themes to change the action the blog description outputs as well as the css class.
- Added a `lsx_blog_customizer_show_switcher` filter to allow 3rd party plugins to include the switcher on custom post types.
- Extended the placeholder to the page post type as well.
- Aded CMB2 and included and enabled taxonomy custom fields (tagline, etc) + featured image + icon image + banner image on Blog Categories and Blog Tags.

### Deprecated
- Removed the CMB and UIX vendors.

### Update

- Updating language files.
- Updated the placeholder text and description

### Security

- Updating dependencies to prevent vulnerabilities.
- Updating PHPCS options for better code.
- General testing to ensure compatibility with latest WordPress version (5.5).
- General testing to ensure compatibility with latest LSX Theme version (2.9).


## [[1.3.5]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.3.5) - 2020-03-30

### Fixed

- Fixed issue `PHP Deprecated: dbx_post_advanced is deprecated since version 3.7.0! Use add_meta_boxes instead`.

### Security

- General testing to ensure compatibility with latest WordPress version (5.4).
- General testing to ensure compatibility with latest LSX Theme version (2.7).

## [[1.3.4]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.3.4) - 2019-12-19

### Security

- Checking compatibility with LSX 2.6 release.
- General testing to ensure compatibility with latest WordPress version (5.3).

### Fixes

- Fixes for the blog default layout.
- Spacing fixes on blog archive.
- Added in the missing closing div to the `LSX_Blog_Customizer_Posts` output() function.
- Fix for issue: 'Blog page description outputs a 1 if the description is left blank'.

## [[1.3.3]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.3.3) - 2010-11-13

### Added

- Added in the lazyloading for the slick slider widgets.

### Deprecated

- Removing the reliance on the lazyloading customizer option.

### Fixed

- Fixing styling bugs.

## [[1.3.2]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.3.2) - 2010-09-26

### Fixed

- Fixed the Display Categories option not working as intended.
- Removing grey line when Display Tags is disabled.

## [[1.3.1]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.3.1) - 2010-09-13

### Added

- Added in filters to edit the content part template and path of the widget.

### Fixed

- Updated main_blog_page_carousel function to make sure it does not appears in search pages.
- The arguments for the blog post widget.
- Fix for Display Categories not working as intended.
- Fix for Removing line when no tags are present.

## [[1.3.0]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/1.3.0) - 2010-08-06

### Added

- Dev - Added improvements to the general look and feel of the blog archive, single and pages and the related section.

### Fixed

- Updated the assets and removed the Owl Slider.

## [[1.2.4]](https://github.com/lightspeeddevelopment/lsx-blog-customizer/releases/tag/v1.2.4) - 2010-06-24

### Added

- Added in a filter to overwrite the Main Blog Description - `lsx_blog_customizer_main_blog_page_description`.
- Added in a CSS class `lsx-slick-slider` which allows plugins to use the styling this plugin.
- Added in the WP Trim Excerpt Function.
- Added in a `lsx-search-enabled` body class which shows when a page has facets.
- Fixing the display settings conditional.

### Fixed

- Updated the uix-core.js to remove the Cyclic error when saving the theme options.
- Search styling updates & mobile fixes.

## [[1.2.3]]()

### Fixed

- Fixing deprecated notices.

## [[1.2.2]]()

### Fixed

- Fixing deprecated notices.

## [[1.2.1]]()

### Deprecated

- Removed the Deprecated `create_function` methods.

## [[1.2.0]]()

### Added

- Added in a conditional so the tooltips wont try initiate if the library has not loaded.

### Fixed

- Added jquery-ui-tooltip to the list of dependencies for the frontend scripts.

## [[1.1.1]]()

### Added

- Added compatibility with LSX Videos.
- Added compatibility with LSX Search.

### Fixed

- Fixed blog/search results - Custom post type visual.
- Fixed blog/search results - [moved to LSX] Bottom image only used by LSX Blog Customizer.

## [[1.1.0]]()

### Added

- Added compatibility with LSX 2.0.
- Added compatibility with LSX Sharing.
- UIX copied from TO 1.1 + Fixed issue with sub tabs click (settings).
- New project structure.
- Added two new filters to change the allowed tags (and word count) when the content is cropped to build the excerpt.
- New categories slider.
- Added new widget: most recent posts.
- Added new option: full-width archive/blog page.
- Added new option: hide/show date on blog/archive.
- Added new option: layout switcher.
- Added new option: related posts.
- Added new option: full width option on single post.
- Added new option: display post navigation.

## [[1.0.1]]()

### Added

- WP Customizer: new single post option - display/hide posts navigation
- WP Customizer: new single post feature - related posts
- WP Customizer: new main blog page feature - description
- Enable the theme template overriding

## [[1.0.0]]()

### Added

- First Version
