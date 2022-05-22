// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Collections.Generic;
using Genius.Client.Interfaces;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;

namespace Genius.Client.Controllers;

/// <summary>
/// Provides API for managing the users.
/// </summary>
[ApiController]
[Route("api/[controller]")]
public class UserController : ControllerBase
{
    private readonly ILogger<UserController> _logger;

    private readonly Genius.Protocol.User.UserClient _userClient;

    private readonly Genius.Protocol.OAuth.OAuthClient _oAuthClient;

    public UserController(ILogger<UserController> logger, IChannel channel)
    {
        _logger = logger;
        _userClient = new Genius.Protocol.User.UserClient(channel.GetOAuthChannel());
        _oAuthClient = new Genius.Protocol.OAuth.OAuthClient(channel.GetOAuthChannel());
    }

    [HttpGet]
    public IEnumerable<int> Get()
    {
        return new List<int> { 1, 2, 3, 4 };
    }
}
