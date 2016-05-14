
/**
 * Represents a URL
 */
public class MyURL {

  private String scheme = "http";
  private String domainName = null;
  private int port = 80;
  private String path = "/";

  /**
   * Split {@code url} into the various components of a URL
   *
   * @param url the {@code String} to parse
   */
  public MyURL(String url) {

    // TODO:  Split the url into scheme, domainName, port, and path.
    // Only the domainName is required.  Default values given above.
    // See the test file for examples of correct and incorrect behavior.
    // Hints:  (1) My implementation is mostly calls to String.indexOf and String.substring.
    // (2) indexOf can take a String as a parameter (it need not be a single character).

    // Below is the layout of a URL, as described at w3schools.com/html/html_urlencode.asp.
    //   scheme://prefix.domain:port/path/filename
    // For MyURL, path and filename will be combined and considered as 'path'
    // and prefix and domain will be combined and considered as 'domainName'

    String originalUrl = url;
    // 1. Find the value of 'scheme' of the url
    // find the index of the first occurrence of "://", which marks the end of the scheme portion
    int endOfScheme = url.indexOf("://");
    scheme = url.substring(0, endOfScheme); // get the scheme from url, excluding end index 
    // 2. Find the value of 'domainName' of the url
    // find the index of next occurrence of ":" AFTER the scheme's "://", 
    // so begin searching at (endOfScheme + 3) so the "://" text is not included in the search
    String urlAfterScheme = url.substring(endOfScheme + 3);
    int endOfDomain = urlAfterScheme.indexOf(':'); // index of delimiter between domain and port
    domainName = urlAfterScheme.substring(0, endOfDomain); // get the domainName, excluding end index
    // 3. Find the value of 'port' of the url
    // find index of next occurence of "/" AFTER the colon delimiter
    // search area is part of urlWithoutScheme following index of ":" plus 1
    String urlAfterDomain = urlAfterScheme.substring(endOfDomain + 1);
    int endOfPort = urlAfterDomain.indexOf('/');
    port = Integer.parseInt(urlAfterDomain.substring(0, endOfPort)); // convert port string to Integer
    // 4. Find the value of 'path' of the url
    // since both path and filename are considered combined for MyUrl,
    // then the remaining text of the url will be the path.
    // The remaining text will equal all text following the endOfPort index.
    path = url.substring(endOfPort); // include the '/' in the path string

  }

  /**
   * If {@code newURL} has a scheme (e.g., begins with "http://", "ftp://", etc), then parse {@code newURL} 
   * and ignore {@code currentURL}.  If {@code newURL} does not have a scheme, then assume it is intended 
   * to be a relative link and replace the file component of {@code currentURL}'s path with {@code newURL}.
   *
   * @param newURL     a {@code String} representing the new URL.
   * @param currentURL the current URL
   */
  public MyURL(String newURL, MyURL currentURL) {

    // TODO: If newURL has a scheme, then take the same action as the other constructor.
    // If newURL does not have a scheme
    // (1) Make a copy of currentURL
    // (2) Replace the filename (i.e., the last segment of the path) with the relative link.
    // See the test file for examples of correct and incorrect behavior.
    // Hint:  Consider using String.lastIndexOf
  }


  public String scheme() {
    return scheme;
  }

  public String domainName() {
    return domainName;
  }

  public int port() {
    return port;
  }

  public String path() {
    return path;
  }

  /**
   * Format this URL as a {@code String}
   *
   * @return this URL formatted as a string.
   */
  public String toString() {
    // TODO:  Format this URL as a string
    return String.format("");
  }

  // Needed in order to use MyURL as a key to a HashMap
  @Override
  public int hashCode() {
    return toString().hashCode();
  }

  // Needed in order to use MyURL as a key to a HashMap
  @Override
  public boolean equals(Object other) {
    if (other instanceof MyURL) {
      MyURL otherURL = (MyURL) other;
      return this.scheme.equals(otherURL.scheme) &&
          this.domainName.equals(otherURL.domainName) &&
          this.port == otherURL.port() &&
          this.path.equals(otherURL.path);
    } else {
      return false;
    }
  }
} // end class
